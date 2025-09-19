<?php
require_once __DIR__ . '/db.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function is_post(){ return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function redirect($path){ header('Location: '.$path); exit; }

function current_user(){ return $_SESSION['user'] ?? null; }
function require_login(){
    if (!current_user()) redirect(APP_URL.'/?r=auth/login');
}

function csrf_token(){
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function check_csrf(){
    if (!is_post()) return;
    $t = $_POST['csrf'] ?? '';
    if (!$t || !hash_equals($_SESSION['csrf'] ?? '', $t)) {
        http_response_code(400); exit('CSRF inválido.');
    }
}

function log_action($action, $entity=null, $entity_id=null, $details=null){
    $uid = current_user()['id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $st = db()->prepare("INSERT INTO audit_log (user_id, action, entity, entity_id, details, ip) VALUES (?,?,?,?,?,?)");
    $st->execute([$uid,$action,$entity,$entity_id,$details,$ip]);
}

function ensure_admin(){
    $pdo = db();
    $exists = $pdo->query("SELECT COUNT(*) c FROM users")->fetch()['c'] ?? 0;
    if ((int)$exists === 0){
        $st = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)");
        $st->execute(['Administrador','admin@local',password_hash('admin123', PASSWORD_DEFAULT),'admin']);
    }
}

// View renderer
function render($view, $vars=[]){
    extract($vars);
    $viewFile = __DIR__ . '/views/' . $view . '.php';
    if (!file_exists($viewFile)) { http_response_code(404); exit("View não encontrada: $view"); }
    require __DIR__ . '/views/layout/header.php';
    require $viewFile;
    require __DIR__ . '/views/layout/footer.php';
}
