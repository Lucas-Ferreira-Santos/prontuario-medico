<?php
require_once __DIR__ . '/db.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function is_post(){ return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function redirect($path){ header('Location: '.$path); exit; }

function current_user(){ return $_SESSION['user'] ?? null; }
function user_has_role($roles): bool {
  $u = current_user();
  if (!$u) return false;
  $roles = is_array($roles) ? $roles : [$roles];
  if (in_array('admin',$roles,true) && $u['role']==='admin') return true;
  return in_array($u['role'],$roles,true) || $u['role']==='admin';
}
function require_role($roles){
  if (!user_has_role($roles)) { http_response_code(403); exit('Sem permissão.'); }
}

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
// Mantém só dígitos
function cpf_digits(string $cpf): string {
    return preg_replace('/\D+/', '', $cpf) ?? '';
}

// Validação clássica (resto < 2 => DV 0; senão 11 - resto)
function cpf_is_valid(?string $cpf): bool {
    if ($cpf === null) return false;
    $cpf = cpf_digits($cpf);
    if (strlen($cpf) !== 11) return false;
    if (preg_match('/^(.)\1{10}$/', $cpf)) return false; // 000..., 111..., etc.

    // 1º DV
    $sum = 0;
    for ($i = 0, $w = 10; $i < 9; $i++, $w--) $sum += ((int)$cpf[$i]) * $w;
    $rest = $sum % 11;
    $dv1 = ($rest < 2) ? 0 : 11 - $rest;
    if ((int)$cpf[9] !== $dv1) return false;

    // 2º DV
    $sum = 0;
    for ($i = 0, $w = 11; $i < 10; $i++, $w--) $sum += ((int)$cpf[$i]) * $w;
    $rest = $sum % 11;
    $dv2 = ($rest < 2) ? 0 : 11 - $rest;
    if ((int)$cpf[10] !== $dv2) return false;

    return true;
}

// Exibição com máscara
function cpf_mask(?string $cpf): string {
    if ($cpf === null || $cpf === '') return '';
    $d = cpf_digits($cpf);
    if (strlen($d) !== 11) return $cpf;
    return substr($d,0,3).'.'.substr($d,3,3).'.'.substr($d,6,3).'-'.substr($d,9,2);
}
?>

<script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Se a página foi acessada via botão "voltar", recarrega forçadamente
            location.reload(true);
        }
    });
</script>