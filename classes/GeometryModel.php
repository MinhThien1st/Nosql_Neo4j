<?php
/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9
 * File: classes/GeometryModel.php
 * Lớp nghiệp vụ xử lý dữ liệu hình học, đồ thị phả hệ, công thức, chuyển hóa hình học và Quiz Test
 * ==============================================================================
 */

require_once __DIR__ . '/Neo4jService.php';

class GeometryModel
{
    private $neo4j;
    private $fallbackData;

    public function __construct()
    {
        $this->neo4j = Neo4jService::getInstance();
        $this->loadFallbackData();
    }

    /**
     * Kiểm tra xem đang dùng Neo4j thật hay dữ liệu bộ nhớ đệm
     */
    public function isLiveNeo4j()
    {
        return $this->neo4j->isConnected();
    }

    /**
     * Bản đồ phân loại theo khối lớp học (Chương trình GDPT Toán THCS)
     */
    public static function getShapeGrades($shapeId): array
    {
        $map = [
            'tu_giac' => [7, 8],
            'hinh_thang' => [7, 8],
            'hinh_thang_vuong' => [7, 8],
            'hinh_thang_can' => [6, 8],
            'hinh_dieu' => [8],
            'hinh_binh_hanh' => [6, 8],
            'hinh_chu_nhat' => [6, 8],
            'hinh_thoi' => [6, 8],
            'hinh_vuong' => [6, 8],
        ];
        return $map[$shapeId] ?? [8];
    }

    /**
     * Lấy toàn bộ danh sách các hình tứ giác (kèm thông tin lớp học)
     */
    public function getAllShapes()
    {
        $shapes = [];
        if ($this->isLiveNeo4j()) {
            $cypher = "MATCH (s:Shape) RETURN s ORDER BY s.name";
            $res = $this->neo4j->runCypher($cypher);
            if ($res['success'] && !empty($res['data']['data'])) {
                foreach ($res['data']['data'] as $row) {
                    $shape = $row['row'][0];
                    $shape['grades'] = self::getShapeGrades($shape['id'] ?? '');
                    $shape['grade_text'] = 'Toán Lớp ' . implode(', ', $shape['grades']);
                    $shapes[] = $shape;
                }
                return $shapes;
            }
        }

        foreach ($this->fallbackData['shapes'] as $id => $shape) {
            $shape['grades'] = self::getShapeGrades($id);
            $shape['grade_text'] = 'Toán Lớp ' . implode(', ', $shape['grades']);
            $shapes[] = $shape;
        }
        return $shapes;
    }

    /**
     * Lấy danh sách các hình theo khối lớp (6, 7, hoặc 8)
     */
    public function getShapesByGrade(int $grade)
    {
        $all = $this->getAllShapes();
        return array_values(array_filter($all, function($s) use ($grade) {
            return in_array($grade, $s['grades'] ?? []);
        }));
    }

    /**
     * Lấy thông tin chi tiết của 1 hình (kèm thuộc tính, công thức, liên kết)
     */
    public function getShapeById($id)
    {
        if ($this->isLiveNeo4j()) {
            $cypher = "
                MATCH (s:Shape {id: \$id})
                OPTIONAL MATCH (s)-[:HAS_PROPERTY]->(p:Property)
                OPTIONAL MATCH (s)-[:HAS_FORMULA]->(f:Formula)
                OPTIONAL MATCH (parent:Shape)-[r_in:EVOLVES_TO]->(s)
                OPTIONAL MATCH (s)-[r_out:EVOLVES_TO]->(child:Shape)
                RETURN s, collect(DISTINCT p) AS properties, f AS formula, 
                       collect(DISTINCT {parent: parent.name, parent_id: parent.id, condition: r_in.condition}) AS ancestors,
                       collect(DISTINCT {child: child.name, child_id: child.id, condition: r_out.condition}) AS descendants
            ";
            $res = $this->neo4j->runCypher($cypher, ['id' => $id]);
            if ($res['success'] && !empty($res['data']['data'])) {
                $row = $res['data']['data'][0]['row'];
                $shape = $row[0];
                $shape['properties'] = $row[1] ?? [];
                $shape['formula'] = $row[2] ?? [];
                $shape['ancestors'] = $row[3] ?? [];
                $shape['descendants'] = $row[4] ?? [];
                return $shape;
            }
        }

        if (isset($this->fallbackData['shapes'][$id])) {
            return $this->fallbackData['shapes'][$id];
        }
        return null;
    }

    /**
     * Lấy dữ liệu đồ thị cho Vis.js vẽ sơ đồ phả hệ trực quan
     */
    public function getGraphData()
    {
        if ($this->isLiveNeo4j()) {
            $cypher = "
                MATCH (s1)-[r]->(s2)
                WHERE (s1:Category OR s1:Shape) AND (s2:Shape)
                RETURN s1.id AS source_id, s1.name AS source_name, labels(s1)[0] AS source_label,
                       type(r) AS rel_type, r.condition AS condition,
                       s2.id AS target_id, s2.name AS target_name, labels(s2)[0] AS target_label,
                       s2.color AS target_color
            ";
            $res = $this->neo4j->runCypher($cypher);
            if ($res['success'] && !empty($res['data']['data'])) {
                $nodes = [];
                $edges = [];
                $nodeMap = [];

                foreach ($res['data']['data'] as $row) {
                    $r = $row['row'];
                    $srcId = $r[0];
                    $srcName = $r[1];
                    $srcLabel = $r[2];
                    $relType = $r[3];
                    $condition = $r[4] ?? '';
                    $dstId = $r[5];
                    $dstName = $r[6];
                    $dstLabel = $r[7];
                    $dstColor = $r[8] ?? '#2563EB';

                    if (!isset($nodeMap[$srcId])) {
                        $nodeMap[$srcId] = true;
                        $nodes[] = [
                            'id' => $srcId,
                            'label' => $srcName,
                            'group' => $srcLabel,
                            'shape' => 'box',
                            'color' => ($srcLabel === 'Category') ? '#334155' : '#0284C7'
                        ];
                    }

                    if (!isset($nodeMap[$dstId])) {
                        $nodeMap[$dstId] = true;
                        $nodes[] = [
                            'id' => $dstId,
                            'label' => $dstName,
                            'group' => $dstLabel,
                            'shape' => 'box',
                            'color' => $dstColor
                        ];
                    }

                    $edges[] = [
                        'from' => $srcId,
                        'to' => $dstId,
                        'label' => $condition ?: $relType,
                        'arrows' => 'to'
                    ];
                }

                if (!empty($nodes)) {
                    return ['nodes' => $nodes, 'edges' => $edges];
                }
            }
        }

        // Dữ liệu Fallback Graph
        return $this->fallbackData['graph'];
    }

    /**
     * Chuyển hóa hình học: Tìm đường đi ngắn nhất (Shortest Path) giữa 2 hình trong Neo4j
     */
    public function findTransformationPath($startId, $targetId)
    {
        if ($startId === $targetId) {
            return [
                'success' => false,
                'message' => 'Hai hình bạn chọn đang trùng nhau! Vui lòng chọn 2 hình khác nhau để phân tích chuyển hóa.'
            ];
        }

        if ($this->isLiveNeo4j()) {
            $cypher = "
                MATCH (start:Shape {id: \$startId}), (target:Shape {id: \$targetId})
                MATCH p = shortestPath((start)-[:EVOLVES_TO*]->(target))
                RETURN [node in nodes(p) | {id: node.id, name: node.name, color: node.color}] AS path_nodes,
                       [rel in relationships(p) | {condition: rel.condition, detail: rel.detail}] AS path_rels
            ";
            $res = $this->neo4j->runCypher($cypher, ['startId' => $startId, 'targetId' => $targetId]);
            if ($res['success'] && !empty($res['data']['data'])) {
                $row = $res['data']['data'][0]['row'];
                return [
                    'success' => true,
                    'is_direct' => true,
                    'nodes' => $row[0],
                    'relationships' => $row[1],
                    'steps_count' => count($row[1])
                ];
            }

            $cypherUndirected = "
                MATCH (start:Shape {id: \$startId}), (target:Shape {id: \$targetId})
                MATCH p = shortestPath((start)-[:EVOLVES_TO*]-(target))
                RETURN [node in nodes(p) | {id: node.id, name: node.name, color: node.color}] AS path_nodes,
                       [rel in relationships(p) | {condition: rel.condition, detail: rel.detail}] AS path_rels
            ";
            $res2 = $this->neo4j->runCypher($cypherUndirected, ['startId' => $startId, 'targetId' => $targetId]);
            if ($res2['success'] && !empty($res2['data']['data'])) {
                $row = $res2['data']['data'][0]['row'];
                return [
                    'success' => true,
                    'is_direct' => false,
                    'nodes' => $row[0],
                    'relationships' => $row[1],
                    'steps_count' => count($row[1]),
                    'note' => 'Đường đi thông qua hình tổ tiên chung'
                ];
            }
        }

        return $this->bfsFallbackPath($startId, $targetId);
    }

    /**
     * So sánh 2 hình: đặc tính giống nhau và khác nhau
     */
    public function compareShapes($id1, $id2)
    {
        $shape1 = $this->getShapeById($id1);
        $shape2 = $this->getShapeById($id2);

        if (!$shape1 || !$shape2) {
            return null;
        }

        $props1 = array_column($shape1['properties'] ?? [], 'name');
        $props2 = array_column($shape2['properties'] ?? [], 'name');

        $common = array_values(array_intersect($props1, $props2));
        $only1 = array_values(array_diff($props1, $props2));
        $only2 = array_values(array_diff($props2, $props1));

        return [
            'shape1' => $shape1,
            'shape2' => $shape2,
            'common_properties' => $common,
            'only_shape1' => $only1,
            'only_shape2' => $only2
        ];
    }

    /**
     * Tính toán chu vi và diện tích với các bước giải chi tiết
     */
    public function calculate($shapeId, $inputs)
    {
        $p = 0;
        $s = 0;
        $steps = [];

        switch ($shapeId) {
            case 'hinh_vuong':
                $a = floatval($inputs['a'] ?? 0);
                $p = 4 * $a;
                $s = $a * $a;
                $steps[] = "Bước 1: Tính Chu vi: P = 4 × a = 4 × {$a} = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = a² = {$a} × {$a} = <strong>{$s}</strong>";
                break;

            case 'hinh_chu_nhat':
                $a = floatval($inputs['a'] ?? 0);
                $b = floatval($inputs['b'] ?? 0);
                $p = 2 * ($a + $b);
                $s = $a * $b;
                $steps[] = "Bước 1: Tính Chu vi: P = (dài + rộng) × 2 = ({$a} + {$b}) × 2 = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = dài × rộng = {$a} × {$b} = <strong>{$s}</strong>";
                break;

            case 'hinh_binh_hanh':
                $a = floatval($inputs['a'] ?? 0);
                $b = floatval($inputs['b'] ?? 0);
                $h = floatval($inputs['h'] ?? 0);
                $p = 2 * ($a + $b);
                $s = $a * $h;
                $steps[] = "Bước 1: Tính Chu vi: P = 2 × (đáy + cạnh bên) = 2 × ({$a} + {$b}) = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = đáy × chiều cao = {$a} × {$h} = <strong>{$s}</strong>";
                break;

            case 'hinh_thoi':
                $a = floatval($inputs['a'] ?? 0);
                $d1 = floatval($inputs['d1'] ?? 0);
                $d2 = floatval($inputs['d2'] ?? 0);
                $p = 4 * $a;
                $s = ($d1 * $d2) / 2;
                $steps[] = "Bước 1: Tính Chu vi: P = 4 × cạnh = 4 × {$a} = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = (d₁ × d₂) / 2 = ({$d1} × {$d2}) / 2 = <strong>{$s}</strong>";
                break;

            case 'hinh_thang':
            case 'hinh_thang_vuong':
            case 'hinh_thang_can':
                $a = floatval($inputs['a'] ?? 0);
                $b = floatval($inputs['b'] ?? 0);
                $c = floatval($inputs['c'] ?? 0);
                $d = floatval($inputs['d'] ?? 0);
                $h = floatval($inputs['h'] ?? 0);
                $p = $a + $b + $c + $d;
                $s = (($a + $b) * $h) / 2;
                $steps[] = "Bước 1: Tính Chu vi: P = a + b + c + d = {$a} + {$b} + {$c} + {$d} = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = ((đáy lớn + đáy bé) × chiều cao) / 2 = (({$a} + {$b}) × {$h}) / 2 = <strong>{$s}</strong>";
                break;

            case 'hinh_dieu':
                $a = floatval($inputs['a'] ?? 0);
                $b = floatval($inputs['b'] ?? 0);
                $d1 = floatval($inputs['d1'] ?? 0);
                $d2 = floatval($inputs['d2'] ?? 0);
                $p = 2 * ($a + $b);
                $s = ($d1 * $d2) / 2;
                $steps[] = "Bước 1: Tính Chu vi: P = 2 × (a + b) = 2 × ({$a} + {$b}) = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Tính Diện tích: S = (d₁ × d₂) / 2 = ({$d1} × {$d2}) / 2 = <strong>{$s}</strong>";
                break;

            case 'tu_giac':
            default:
                $a = floatval($inputs['a'] ?? 0);
                $b = floatval($inputs['b'] ?? 0);
                $c = floatval($inputs['c'] ?? 0);
                $d = floatval($inputs['d'] ?? 0);
                $p = $a + $b + $c + $d;
                $steps[] = "Bước 1: Tính Chu vi: P = tổng 4 cạnh = {$a} + {$b} + {$c} + {$d} = <strong>{$p}</strong>";
                $steps[] = "Bước 2: Diện tích tứ giác bất kỳ được xác định bằng cách chia thành hai tam giác liên kết.";
                break;
        }

        return [
            'perimeter' => round($p, 2),
            'area' => round($s, 2),
            'steps' => $steps
        ];
    }

    /**
     * Ngân hàng câu hỏi Quiz Test chuẩn toán học
     */
    public function getQuizQuestions()
    {
        return [
            [
                'id' => 1,
                'type' => 'guess_shape',
                'question' => 'Hình tứ giác nào có 4 cạnh bằng nhau VÀ đồng thời có 4 góc vuông 90°?',
                'hint' => 'Hình học hoàn thiện cả tính chất của hình chữ nhật và hình thoi.',
                'options' => ['Hình vuông', 'Hình chữ nhật', 'Hình thoi', 'Hình thang cân'],
                'answer' => 'Hình vuông',
                'explanation' => 'Chính xác! Hình vuông có 4 cạnh bằng nhau và 4 góc đều là góc vuông 90°.'
            ],
            [
                'id' => 2,
                'type' => 'formula',
                'question' => 'Công thức tính diện tích: S = ((a + b) × h) / 2 là công thức tính diện tích của hình nào?',
                'hint' => 'Hình có đúng một cặp cạnh đối song song với nhau.',
                'options' => ['Hình thang', 'Hình bình hành', 'Hình diều', 'Hình chữ nhật'],
                'answer' => 'Hình thang',
                'explanation' => 'Đúng! Diện tích hình thang bằng tổng hai đáy nhân chiều cao rồi chia 2.'
            ],
            [
                'id' => 3,
                'type' => 'evolution',
                'question' => 'Để một "Hình bình hành" trở thành "Hình chữ nhật", cần bổ sung thêm điều kiện nào?',
                'hint' => 'Liên quan đến số đo góc hoặc tính chất hai đường chéo.',
                'options' => ['Có 1 góc vuông (hoặc 2 đường chéo bằng nhau)', 'Có 4 cạnh bằng nhau', 'Có hai đường chéo vuông góc', 'Có hai cạnh kề bằng nhau'],
                'answer' => 'Có 1 góc vuông (hoặc 2 đường chéo bằng nhau)',
                'explanation' => 'Chính xác! Hình bình hành có một góc vuông (hoặc hai đường chéo bằng nhau) sẽ là hình chữ nhật.'
            ],
            [
                'id' => 4,
                'type' => 'guess_shape',
                'question' => 'Hình tứ giác có hai đường chéo vuông góc với nhau và 4 cạnh bằng nhau là hình gì?',
                'hint' => 'Là hình bình hành có hai đường chéo vuông góc.',
                'options' => ['Hình thoi', 'Hình chữ nhật', 'Hình thang vuông', 'Hình bình hành'],
                'answer' => 'Hình thoi',
                'explanation' => 'Chính xác! Hình thoi có 4 cạnh bằng nhau và hai đường chéo vuông góc tại trung điểm.'
            ],
            [
                'id' => 5,
                'type' => 'calculation',
                'question' => 'Một mảnh đất hình chữ nhật có chiều dài 12m và chiều rộng 7m. Diện tích mảnh đất này là bao nhiêu?',
                'hint' => 'Công thức diện tích: S = dài × rộng.',
                'options' => ['84 m²', '38 m²', '19 m²', '96 m²'],
                'answer' => '84 m²',
                'explanation' => 'Chính xác! Diện tích S = 12 × 7 = 84 m².'
            ],
            [
                'id' => 6,
                'type' => 'guess_shape',
                'question' => 'Hình tứ giác có hai cặp cạnh kề nhau bằng nhau từng đôi một được gọi là hình gì?',
                'hint' => 'Tên tiếng Anh là Kite, hai đường chéo vuông góc.',
                'options' => ['Hình diều', 'Hình thoi', 'Hình thang', 'Hình bình hành'],
                'answer' => 'Hình diều',
                'explanation' => 'Chính xác! Hình diều có hai cặp cạnh kề bằng nhau và hai đường chéo vuông góc.'
            ]
        ];
    }

    private function bfsFallbackPath($startId, $targetId)
    {
        $edges = $this->fallbackData['graph']['edges'];
        $adj = [];
        $conds = [];

        foreach ($edges as $e) {
            $from = $e['from'];
            $to = $e['to'];
            $adj[$from][] = $to;
            $conds[$from . '->' . $to] = $e['label'];
        }

        $queue = [[$startId]];
        $visited = [$startId => true];

        while (!empty($queue)) {
            $path = array_shift($queue);
            $curr = end($path);

            if ($curr === $targetId) {
                $nodes = [];
                $rels = [];
                for ($i = 0; $i < count($path); $i++) {
                    $nodeId = $path[$i];
                    $shape = $this->fallbackData['shapes'][$nodeId] ?? null;
                    $nodes[] = [
                        'id' => $nodeId,
                        'name' => $shape['name'] ?? $nodeId,
                        'color' => $shape['color'] ?? '#2563EB'
                    ];
                    if ($i > 0) {
                        $prevId = $path[$i - 1];
                        $rels[] = [
                            'condition' => $conds[$prevId . '->' . $nodeId] ?? 'Chuyển hóa',
                            'detail' => "Bổ sung điều kiện từ {$prevId} sang {$nodeId}"
                        ];
                    }
                }
                return [
                    'success' => true,
                    'is_direct' => true,
                    'nodes' => $nodes,
                    'relationships' => $rels,
                    'steps_count' => count($rels)
                ];
            }

            foreach ($adj[$curr] ?? [] as $neighbor) {
                if (!isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $newPath = $path;
                    $newPath[] = $neighbor;
                    $queue[] = $newPath;
                }
            }
        }

        return [
            'success' => false,
            'message' => "Không tìm thấy đường chuyển hóa trực tiếp một chiều từ {$startId} đến {$targetId}."
        ];
    }

    private function loadFallbackData()
    {
        $this->fallbackData = [
            'shapes' => [
                'tu_giac' => [
                    'id' => 'tu_giac',
                    'name' => 'Tứ giác',
                    'name_en' => 'Quadrilateral',
                    'alias' => 'Tứ giác lồi',
                    'color' => '#0284C7',
                    'badge' => 'Cấp độ 1',
                    'definition' => 'Tứ giác là đa giác có 4 cạnh, 4 đỉnh và 4 góc. Tổng 4 góc trong một tứ giác luôn bằng 360 độ.',
                    'fun_fact' => 'Mọi tứ giác lồi bất kỳ đều có thể chia thành 2 hình tam giác bằng một đường chéo.',
                    'real_life' => 'Cánh buồm thuyền, mảng thiết kế đa giác kiến trúc.',
                    'formula' => [
                        'perimeter_formula' => 'P = a + b + c + d',
                        'perimeter_desc' => 'Chu vi bằng tổng độ dài 4 cạnh.',
                        'area_formula' => 'S = S₁ + S₂',
                        'area_desc' => 'Chia tứ giác thành hai tam giác liên kết rồi cộng diện tích lại.'
                    ],
                    'properties' => [
                        ['name' => 'Có 4 cạnh, 4 đỉnh, 4 góc'],
                        ['name' => 'Tổng số đo 4 góc trong luôn bằng 360°']
                    ]
                ],
                'hinh_thang' => [
                    'id' => 'hinh_thang',
                    'name' => 'Hình thang',
                    'name_en' => 'Trapezoid',
                    'alias' => 'Hình thang',
                    'color' => '#0EA5E9',
                    'badge' => 'Cấp độ 2',
                    'definition' => 'Hình thang là tứ giác có một cặp cạnh đối diện song song với nhau (gọi là hai đáy).',
                    'fun_fact' => 'Tên Trapezoid bắt nguồn từ tiếng Hy Lạp "trapeza" mang nghĩa là chiếc bàn.',
                    'real_life' => 'Mái nhà kiểu Thái, túi xách thời trang, thang leo, chậu cây.',
                    'formula' => [
                        'perimeter_formula' => 'P = a + b + c + d',
                        'perimeter_desc' => 'Tổng độ dài của 2 đáy và 2 cạnh bên.',
                        'area_formula' => 'S = ((a + b) × h) / 2',
                        'area_desc' => 'Tổng độ dài hai đáy nhân với chiều cao, tất cả chia cho 2.'
                    ],
                    'properties' => [
                        ['name' => 'Có một cặp cạnh đối song song (đáy lớn, đáy nhỏ)'],
                        ['name' => 'Tổng 4 góc bằng 360°']
                    ]
                ],
                'hinh_thang_vuong' => [
                    'id' => 'hinh_thang_vuong',
                    'name' => 'Hình thang vuông',
                    'name_en' => 'Right Trapezoid',
                    'alias' => 'Hình thang vuông',
                    'color' => '#06B6D4',
                    'badge' => 'Cấp độ 2.1',
                    'definition' => 'Hình thang vuông là hình thang có ít nhất một góc vuông. Cạnh bên vuông góc với hai đáy chính là đường cao.',
                    'fun_fact' => 'Chiều cao của hình thang vuông bằng chính độ dài của cạnh bên vuông góc.',
                    'real_life' => 'Bậc thềm kiến trúc, tấm chắn gió công nghiệp.',
                    'formula' => [
                        'perimeter_formula' => 'P = a + b + c + h',
                        'perimeter_desc' => 'Tổng 4 cạnh quanh hình thang vuông.',
                        'area_formula' => 'S = ((a + b) × h) / 2',
                        'area_desc' => 'Tổng hai đáy nhân chiều cao chia đôi.'
                    ],
                    'properties' => [
                        ['name' => 'Có một cặp cạnh đối song song'],
                        ['name' => 'Có ít nhất 1 góc vuông (90°)']
                    ]
                ],
                'hinh_thang_can' => [
                    'id' => 'hinh_thang_can',
                    'name' => 'Hình thang cân',
                    'name_en' => 'Isosceles Trapezoid',
                    'alias' => 'Hình thang cân',
                    'color' => '#14B8A6',
                    'badge' => 'Cấp độ 2.2',
                    'definition' => 'Hình thang cân là hình thang có hai góc kề một đáy bằng nhau, hoặc hai cạnh bên bằng nhau và hai đường chéo bằng nhau.',
                    'fun_fact' => 'Hình thang cân có 1 trục đối xứng đi qua trung điểm hai đáy.',
                    'real_life' => 'Mặt cắt bát ăn, chụp đèn bàn, kết cấu cầu vòm.',
                    'formula' => [
                        'perimeter_formula' => 'P = a + b + 2c',
                        'perimeter_desc' => 'Đáy lớn + đáy nhỏ + 2 lần cạnh bên.',
                        'area_formula' => 'S = ((a + b) × h) / 2',
                        'area_desc' => 'Tổng hai đáy nhân chiều cao chia đôi.'
                    ],
                    'properties' => [
                        ['name' => 'Có một cặp cạnh đối song song'],
                        ['name' => 'Hai góc kề một đáy bằng nhau'],
                        ['name' => 'Hai đường chéo có độ dài bằng nhau']
                    ]
                ],
                'hinh_dieu' => [
                    'id' => 'hinh_dieu',
                    'name' => 'Hình diều',
                    'name_en' => 'Kite',
                    'alias' => 'Hình cánh diều',
                    'color' => '#F97316',
                    'badge' => 'Cấp độ 2',
                    'definition' => 'Hình diều là tứ giác có hai cặp cạnh kề nhau bằng nhau. Hai đường chéo vuông góc với nhau và một đường chéo là trung trực của đường chéo kia.',
                    'fun_fact' => 'Hai đường chéo vuông góc 90 độ tạo sự cân bằng khí động học tối ưu.',
                    'real_life' => 'Cánh diều thể thao, mặt cắt đá quý mài giác diều.',
                    'formula' => [
                        'perimeter_formula' => 'P = 2 × (a + b)',
                        'perimeter_desc' => 'Chu vi bằng 2 lần tổng hai cạnh kề khác nhau.',
                        'area_formula' => 'S = (d₁ × d₂) / 2',
                        'area_desc' => 'Diện tích bằng tích hai đường chéo chia đôi.'
                    ],
                    'properties' => [
                        ['name' => 'Có 2 cặp cạnh kề nhau bằng nhau'],
                        ['name' => 'Hai đường chéo vuông góc với nhau']
                    ]
                ],
                'hinh_binh_hanh' => [
                    'id' => 'hinh_binh_hanh',
                    'name' => 'Hình bình hành',
                    'name_en' => 'Parallelogram',
                    'alias' => 'Hình bình hành',
                    'color' => '#38BDF8',
                    'badge' => 'Cấp độ 3',
                    'definition' => 'Hình bình hành là tứ giác có hai cặp cạnh đối song song và bằng nhau, các góc đối bằng nhau, hai đường chéo cắt nhau tại trung điểm của mỗi đường.',
                    'fun_fact' => 'Nối trung điểm 4 cạnh của bất kỳ tứ giác nào luôn tạo ra một hình bình hành (Định lý Varignon).',
                    'real_life' => 'Cánh cửa trượt xếp, bóng đổ kiến trúc, thiết kế khung nâng thủy lực.',
                    'formula' => [
                        'perimeter_formula' => 'P = 2 × (a + b)',
                        'perimeter_desc' => 'Chu vi bằng 2 lần tổng độ dài 2 cạnh kề.',
                        'area_formula' => 'S = a × h',
                        'area_desc' => 'Diện tích bằng cạnh đáy nhân với chiều cao tương ứng.'
                    ],
                    'properties' => [
                        ['name' => 'Hai cặp cạnh đối song song và bằng nhau'],
                        ['name' => 'Các góc đối bằng nhau'],
                        ['name' => 'Hai đường chéo cắt nhau tại trung điểm mỗi đường']
                    ]
                ],
                'hinh_chu_nhat' => [
                    'id' => 'hinh_chu_nhat',
                    'name' => 'Hình chữ nhật',
                    'name_en' => 'Rectangle',
                    'alias' => 'Hình chữ nhật',
                    'color' => '#10B981',
                    'badge' => 'Cấp độ 4',
                    'definition' => 'Hình chữ nhật là tứ giác có 4 góc vuông (90 độ). Nó là trường hợp đặc biệt của hình bình hành có hai đường chéo bằng nhau.',
                    'fun_fact' => 'Hình chữ nhật với tỷ lệ vàng (Golden Rectangle) được áp dụng rộng rãi trong nghệ thuật và kiến trúc Hy Lạp.',
                    'real_life' => 'Màn hình máy tính, điện thoại, sách vở, bàn học, khung cửa sổ.',
                    'formula' => [
                        'perimeter_formula' => 'P = 2 × (a + b)',
                        'perimeter_desc' => 'Chu vi bằng (chiều dài + chiều rộng) nhân 2.',
                        'area_formula' => 'S = a × b',
                        'area_desc' => 'Diện tích bằng chiều dài nhân với chiều rộng.'
                    ],
                    'properties' => [
                        ['name' => 'Có 4 góc vuông (90°)'],
                        ['name' => 'Hai cặp cạnh đối song song và bằng nhau'],
                        ['name' => 'Hai đường chéo có độ dài bằng nhau'],
                        ['name' => 'Hai đường chéo cắt nhau tại trung điểm mỗi đường']
                    ]
                ],
                'hinh_thoi' => [
                    'id' => 'hinh_thoi',
                    'name' => 'Hình thoi',
                    'name_en' => 'Rhombus',
                    'alias' => 'Hình thoi',
                    'color' => '#059669',
                    'badge' => 'Cấp độ 4',
                    'definition' => 'Hình thoi là tứ giác có 4 cạnh dài bằng nhau. Hai đường chéo vuông góc với nhau tại trung điểm của mỗi đường và là phân giác các góc.',
                    'fun_fact' => 'Hình thoi vừa là hình bình hành đặc biệt (4 cạnh bằng nhau), vừa là hình diều đặc biệt.',
                    'real_life' => 'Họa tiết thổ cẩm, biển báo giao thông cảnh báo, mặt cắt kim cương.',
                    'formula' => [
                        'perimeter_formula' => 'P = 4 × a',
                        'perimeter_desc' => 'Chu vi bằng độ dài 1 cạnh nhân 4.',
                        'area_formula' => 'S = (d₁ × d₂) / 2',
                        'area_desc' => 'Diện tích bằng tích hai đường chéo chia 2.'
                    ],
                    'properties' => [
                        ['name' => 'Có 4 cạnh bằng nhau'],
                        ['name' => 'Hai cặp cạnh đối song song'],
                        ['name' => 'Hai đường chéo vuông góc với nhau'],
                        ['name' => 'Hai đường chéo cắt nhau tại trung điểm mỗi đường']
                    ]
                ],
                'hinh_vuong' => [
                    'id' => 'hinh_vuong',
                    'name' => 'Hình vuông',
                    'name_en' => 'Square',
                    'alias' => 'Hình vuông chuẩn',
                    'color' => '#EC4899',
                    'badge' => 'Cấp độ 5 - Hoàn hảo',
                    'definition' => 'Hình vuông là tứ giác đều có 4 góc vuông và 4 cạnh bằng nhau. Nó đồng thời là hình chữ nhật đặc biệt và hình thoi đặc biệt.',
                    'fun_fact' => 'Hình vuông sở hữu 4 trục đối xứng và 1 tâm đối xứng. Trong mọi hình chữ nhật có cùng chu vi, hình vuông có diện tích lớn nhất.',
                    'real_life' => 'Khối Rubik, gạch lát nền, mặt xúc xắc, bàn cờ vua.',
                    'formula' => [
                        'perimeter_formula' => 'P = 4 × a',
                        'perimeter_desc' => 'Chu vi bằng độ dài cạnh nhân 4.',
                        'area_formula' => 'S = a × a = a²',
                        'area_desc' => 'Diện tích bằng bình phương độ dài cạnh.'
                    ],
                    'properties' => [
                        ['name' => 'Có 4 cạnh bằng nhau'],
                        ['name' => 'Có 4 góc vuông (90°)'],
                        ['name' => 'Hai cặp cạnh đối song song'],
                        ['name' => 'Hai đường chéo có độ dài bằng nhau và vuông góc tại trung điểm']
                    ]
                ]
            ],
            'graph' => [
                'nodes' => [
                    ['id' => 'hinh_hoc_phang', 'label' => "Hình học phẳng", 'shape' => 'box', 'color' => '#1E293B'],
                    ['id' => 'tu_giac', 'label' => "Tứ giác", 'shape' => 'box', 'color' => '#0284C7'],
                    ['id' => 'hinh_thang', 'label' => "Hình thang", 'shape' => 'box', 'color' => '#0EA5E9'],
                    ['id' => 'hinh_dieu', 'label' => "Hình diều", 'shape' => 'box', 'color' => '#EA580C'],
                    ['id' => 'hinh_binh_hanh', 'label' => "Hình bình hành", 'shape' => 'box', 'color' => '#0284C7'],
                    ['id' => 'hinh_chu_nhat', 'label' => "Hình chữ nhật", 'shape' => 'box', 'color' => '#059669'],
                    ['id' => 'hinh_thoi', 'label' => "Hình thoi", 'shape' => 'box', 'color' => '#0D9488'],
                    ['id' => 'hinh_vuong', 'label' => "Hình vuông", 'shape' => 'box', 'color' => '#DB2777']
                ],
                'edges' => [
                    ['from' => 'hinh_hoc_phang', 'to' => 'tu_giac', 'label' => '4 cạnh, 4 đỉnh, 4 góc, tổng = 360°'],
                    ['from' => 'tu_giac', 'to' => 'hinh_thang', 'label' => 'Có một cặp cạnh đối song song'],
                    ['from' => 'tu_giac', 'to' => 'hinh_dieu', 'label' => 'Có 2 cặp cạnh kề nhau bằng nhau'],
                    ['from' => 'tu_giac', 'to' => 'hinh_binh_hanh', 'label' => 'Hai cặp cạnh đối song song & bằng nhau'],
                    ['from' => 'hinh_binh_hanh', 'to' => 'hinh_chu_nhat', 'label' => 'Có 4 góc vuông; 2 đường chéo bằng nhau'],
                    ['from' => 'hinh_binh_hanh', 'to' => 'hinh_thoi', 'label' => '4 cạnh bằng nhau; 2 đường chéo vuông góc'],
                    ['from' => 'hinh_chu_nhat', 'to' => 'hinh_vuong', 'label' => '4 cạnh bằng nhau'],
                    ['from' => 'hinh_thoi', 'to' => 'hinh_vuong', 'label' => 'Có 4 góc vuông']
                ]
            ]
        ];
    }
}
