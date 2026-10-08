<?php
/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9
 * File: api.php - API trung tâm phục vụ tương tác AJAX cho giao diện học sinh
 * ==============================================================================
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/classes/GeometryModel.php';
require_once __DIR__ . '/classes/Neo4jService.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$model = new GeometryModel();
$neo4j = Neo4jService::getInstance();

switch ($action) {
    case 'get_graph':
        $graph = $model->getGraphData();
        echo json_encode([
            'success' => true,
            'is_live' => $model->isLiveNeo4j(),
            'data' => $graph
        ]);
        break;

    case 'get_shape':
        $id = $_GET['id'] ?? '';
        $shape = $model->getShapeById($id);
        if ($shape) {
            echo json_encode(['success' => true, 'data' => $shape]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy hình']);
        }
        break;

    case 'transform':
        $start = $_GET['start'] ?? $_POST['start'] ?? '';
        $target = $_GET['target'] ?? $_POST['target'] ?? '';
        $res = $model->findTransformationPath($start, $target);
        echo json_encode($res);
        break;

    case 'compare':
        $s1 = $_GET['s1'] ?? $_POST['s1'] ?? '';
        $s2 = $_GET['s2'] ?? $_POST['s2'] ?? '';
        $res = $model->compareShapes($s1, $s2);
        if ($res) {
            echo json_encode(['success' => true, 'data' => $res]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể so sánh 2 hình đã chọn']);
        }
        break;

    case 'calculate':
        $shapeId = $_GET['shape'] ?? $_POST['shape'] ?? '';
        $inputs = array_merge($_GET, $_POST);
        $res = $model->calculate($shapeId, $inputs);
        echo json_encode(['success' => true, 'data' => $res]);
        break;

    case 'test_neo4j':
        $ping = $neo4j->ping();
        echo json_encode($ping);
        break;

    case 'import_cypher':
        $filePath = CYPHER_SETUP_FILE;
        $importRes = $neo4j->importCypherScript($filePath);
        echo json_encode($importRes);
        break;

    case 'run_cypher':
        $query = $_POST['cypher'] ?? $_GET['cypher'] ?? '';
        if (empty(trim($query))) {
            echo json_encode(['success' => false, 'error' => 'Câu lệnh Cypher trống']);
            break;
        }
        $res = $neo4j->runCypher($query);
        echo json_encode($res);
        break;

    default:
        echo json_encode([
            'success' => false,
            'message' => 'Hành động không hợp lệ',
            'available_actions' => [
                'get_graph', 'get_shape', 'transform', 'compare', 'calculate', 
                'test_neo4j', 'import_cypher', 'run_cypher'
            ]
        ]);
        break;
}
