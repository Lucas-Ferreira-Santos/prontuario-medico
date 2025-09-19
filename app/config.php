<?php
// Configurações gerais
define('APP_NAME', 'Prontuário Médico');
define('APP_BASE', dirname(__DIR__));          
define('APP_URL', '/prontuario/public');       

date_default_timezone_set('America/Belem');

// Credenciais MySQL (XAMPP: root sem senha padrão)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'prontuario');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_CHARSET', 'utf8mb4');
