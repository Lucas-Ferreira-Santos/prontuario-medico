<?php
// Configurações gerais
define('APP_NAME', 'Prontuário Médico');
define('APP_BASE', dirname(__DIR__));

// Se o projeto estiver em public_html/prontuario/public,
// mantenha assim. Se publicar na raiz do domínio, mude para '/'.
define('APP_URL', '/prontuario/public');

date_default_timezone_set('America/Belem');

// Credenciais MySQL (HostGator)
define('DB_HOST', 'localhost');                  // HostGator usa 'localhost'
define('DB_NAME', 'andrer69_prontuario');        // nome do banco com prefixo
define('DB_USER', 'andrer69_lucas');               // usuário MySQL criado no cPanel
define('DB_PASS', 'lucasfrrs136');       // << coloque a senha aqui
define('DB_CHARSET', 'utf8mb4');
