<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;

    // HostGator usa 'localhost' e as credenciais definidas no config.php
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Fallback (se seu provedor exigir socket UNIX, descomente e ajuste o caminho):
        // $socket = '/var/lib/mysql/mysql.sock';
        // $dsn = "mysql:unix_socket=$socket;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        // $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        http_response_code(500);
        exit('Erro ao conectar ao banco de dados. Verifique DB_HOST/DB_NAME/DB_USER/DB_PASS em app/config.php.');
    }

    return $pdo;
}
