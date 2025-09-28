<?php
require_once __DIR__ . '/../app/helpers.php';
ensure_admin(); // cria admin@local se não existir

// router simples via ?r=modulo/acao
$r = $_GET['r'] ?? '';
if ($r === '') {
    if (!current_user()) redirect(APP_URL.'/?r=auth/login');
    render('dashboard');
    exit;
}

[$mod,$act] = array_pad(explode('/', $r, 2), 2, 'index');

switch ($mod) {
  case 'auth':
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $c = new AuthController();
    if ($act==='login') $c->login();
    elseif ($act==='logout') $c->logout();
    else http_response_code(404);
    break;

  case 'patients':
    require_once __DIR__ . '/../app/controllers/PatientController.php';
    $c = new PatientController();
    if ($act==='index') $c->index();
    elseif ($act==='form') $c->form();
    elseif ($act==='save') $c->save();
    elseif ($act==='view') $c->view();
    else http_response_code(404);
    break;

  case 'visits':
    require_once __DIR__ . '/../app/controllers/VisitController.php';
    $c = new VisitController();
    if     ($act==='form') $c->form();
    elseif ($act==='save') $c->save();
    elseif ($act==='view') $c->view();
    else http_response_code(404);
    break;

  case 'triage':
    require_once __DIR__ . '/../app/controllers/TriageController.php';
    $c = new TriageController();
    if     ($act==='index')     $c->index();
    elseif ($act==='form')      $c->form();
    elseif ($act==='save')      $c->save();
    elseif ($act==='view')      $c->view();
    elseif ($act==='set_status')$c->set_status();
    else http_response_code(404);
    break;
    
  case 'triage':
  require_once __DIR__ . '/../app/controllers/TriageController.php';
  $c=new TriageController();
  if     ($act==='index')      $c->index();
  elseif ($act==='form')       $c->form();
  elseif ($act==='save')       $c->save();
  elseif ($act==='view')       $c->view();
  elseif ($act==='set_status') $c->set_status();
  else http_response_code(404);
  break;



  default:
    http_response_code(404);
}
