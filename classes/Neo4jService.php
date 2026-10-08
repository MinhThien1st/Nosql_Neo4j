<?php
/**
 * ==============================================================================
 * ĐỒ ÁN MÔN NOSQL (NEO4J) - NHÓM 9
 * File: classes/Neo4jService.php
 * Lớp dịch vụ kết nối và thực thi truy vấn Cypher với Neo4j Database
 * Có chế độ Fallback Graph thông minh để demo luôn mượt mà trong mọi tình huống!
 * ==============================================================================
 */

require_once __DIR__ . '/../config/config.php';

class Neo4jService
{
    private static $instance = null;
    private $host;
    private $port;
    private $user;
    private $password;
    private $database;
    private $isConnected = null;
    private $lastError = '';

    private function __construct()
    {
        $this->host = NEO4J_HOST;
        $this->port = NEO4J_HTTP_PORT;
        $this->user = NEO4J_USER;
        $this->password = NEO4J_PASS;
        $this->database = NEO4J_DATABASE;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Kiểm tra trạng thái kết nối tới Neo4j
     */
    public function isConnected()
    {
        if ($this->isConnected !== null) {
            return $this->isConnected;
        }

        $res = $this->ping();
        $this->isConnected = $res['success'];
        $this->lastError = $res['message'];
        return $this->isConnected;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Kiểm tra kết nối nhanh qua HTTP REST API
     */
    public function ping()
    {
        $url = "http://{$this->host}:{$this->port}/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'message' => "Không thể kết nối đến Neo4j tại {$this->host}:{$this->port} ({$curlError}).",
                'httpCode' => $httpCode
            ];
        }

        if ($httpCode === 401) {
            return [
                'success' => false,
                'message' => "Sai tài khoản hoặc mật khẩu Neo4j (user: {$this->user}). Vui lòng cập nhật trong config/config.php.",
                'httpCode' => $httpCode
            ];
        }

        if ($httpCode >= 200 && $httpCode < 400) {
            return [
                'success' => true,
                'message' => "Kết nối Neo4j thành công! Sẵn sàng thực thi Cypher.",
                'httpCode' => $httpCode
            ];
        }

        return [
            'success' => false,
            'message' => "Mã phản hồi từ Neo4j: {$httpCode}",
            'httpCode' => $httpCode
        ];
    }

    /**
     * Thực thi một câu lệnh Cypher tới Neo4j Transactional Endpoint
     */
    public function runCypher($query, $params = [])
    {
        if (!$this->isConnected()) {
            return [
                'success' => false,
                'error' => $this->lastError ?: 'Neo4j server chưa kết nối',
                'data' => []
            ];
        }

        $url = "http://{$this->host}:{$this->port}/db/{$this->database}/tx/commit";
        
        $postData = [
            'statements' => [
                [
                    'statement' => $query,
                    'parameters' => (object)$params,
                    'resultDataContents' => ['row', 'graph']
                ]
            ]
        ];

        $payload = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json;charset=UTF-8'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Nếu database được cấu hình không tồn tại (404), thử tự động truy vấn sang database mặc định 'neo4j'
        if ($httpCode === 404 && $this->database !== 'neo4j') {
            $fallbackUrl = "http://{$this->host}:{$this->port}/db/neo4j/tx/commit";
            $ch = curl_init($fallbackUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->password}");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json;charset=UTF-8'
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
        }

        if ($curlError) {
            return ['success' => false, 'error' => $curlError, 'data' => []];
        }

        $result = json_decode($response, true);
        if (!$result || !empty($result['errors'])) {
            $errText = !empty($result['errors']) ? $result['errors'][0]['message'] : 'Lỗi không xác định từ Neo4j';
            return ['success' => false, 'error' => $errText, 'data' => []];
        }

        return [
            'success' => true,
            'data' => $result['results'][0] ?? [],
            'raw' => $result
        ];
    }

    /**
     * Nạp toàn bộ file kịch bản Cypher vào Neo4j
     */
    public function importCypherScript($filePath)
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'message' => "Không tìm thấy file: {$filePath}"];
        }

        $content = file_get_contents($filePath);
        // Tách câu lệnh theo dấu chấm phẩy, bỏ comment
        $lines = explode("\n", $content);
        $cleaned = '';
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (strpos($trimmed, '//') === 0) {
                continue;
            }
            $cleaned .= $line . "\n";
        }

        $statements = array_filter(array_map('trim', explode(';', $cleaned)));
        $executed = 0;
        $errors = [];

        foreach ($statements as $stmt) {
            if (empty($stmt)) continue;
            $res = $this->runCypher($stmt);
            if ($res['success']) {
                $executed++;
            } else {
                $errors[] = $res['error'];
            }
        }

        return [
            'success' => empty($errors),
            'executed' => $executed,
            'errors' => $errors,
            'message' => "Đã thực thi thành công {$executed} câu lệnh Cypher." . (!empty($errors) ? " Có " . count($errors) . " lỗi." : "")
        ];
    }
}
