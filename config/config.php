<?php
try {

    function getCurrentEnvSection()
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if (strpos($host, 'orbitpa.com') !== false)
            return 'orbitpa.com';
        if (strpos($host, 'ranavattra.com') !== false)
            return 'ranavattra.com';
        return 'localhost';
    }

    function loadEnv($filePath = __DIR__ . '/env.ini')
    {
        $section = getCurrentEnvSection();
        $env = parse_ini_file($filePath, true);
        if (!isset($env[$section])) {
            throw new Exception("Environment section [$section] not found in config.");
        }
        return $env[$section];
    }

    function getDbConnection() {
    $config = loadEnv();

    $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
} catch (Exception $e) {
    die("Configuration error: " . $e->getMessage());
}
?>