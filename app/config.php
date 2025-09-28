<?php
// Configurações gerais
define('APP_NAME', 'Prontuário Médico');
define('APP_BASE', dirname(__DIR__));

// Se o projeto está em C:\xampp\htdocs\prontuario\public
// Define APP_URL dinamicamente a partir do caminho do index.php
$__base = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
define('APP_URL', $__base === '' ? '/' : $__base);


date_default_timezone_set('America/Belem');

// Credenciais MySQL (XAMPP padrão)
define('DB_HOST', '127.0.0.1');   // ou 'localhost'
define('DB_NAME', 'prontuario');  // nome do seu banco local
define('DB_USER', 'root');        // XAMPP padrão
define('DB_PASS', '');            // senha vazia no XAMPP
define('DB_CHARSET', 'utf8mb4');
