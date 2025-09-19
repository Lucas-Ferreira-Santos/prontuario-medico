<?php $u = current_user(); ?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e(APP_NAME) ?></title>
<style>
:root{--bg:#0e1217;--card:#151a21;--muted:#93a1b1;--text:#e6edf3;--primary:#2e7dff;}
*{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);font:14px/1.5 system-ui,Segoe UI,Roboto,Arial}
a{color:var(--text);text-decoration:none}
.container{max-width:1100px;margin:0 auto;padding:18px}
.header{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.brand{font-weight:700}
.btn{background:#2a3240;border:1px solid #2a3240;border-radius:10px;padding:8px 12px;cursor:pointer;display:inline-block}
.btn:hover{background:#334055}
.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
.btn.sm{padding:4px 8px;font-size:12px}
.input, input, select, textarea{width:100%;padding:10px;border-radius:10px;border:1px solid #303b4a;background:#0f1520;color:var(--text)}
textarea{min-height:90px}
.table{width:100%;border-collapse:collapse;background:var(--card);border-radius:12px;overflow:hidden}
.table th,.table td{padding:10px;border-bottom:1px solid #223}
.table th{background:#101621;text-align:left}
.card{background:var(--card);border:1px solid #222;border-radius:12px;padding:12px;margin:12px 0}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.col-2{grid-column:span 2}
.bar{display:flex;align-items:center;justify-content:space-between;margin:10px 0}
.muted{color:var(--muted);font-size:12px}
.print-card{background:#fff;color:#000;padding:16px;border-radius:8px}
.print-hidden{display:block}
@media print{
  body{background:#fff;color:#000}
  .header,.bar,.btn{display:none!important}
  .container{max-width:100%;padding:0}
  .card{border:none}
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="brand"><?= e(APP_NAME) ?></div>
    <div>
      <?php if ($u): ?>
        <span class="muted">Olá, <?= e($u['name']) ?> (<?= e($u['role']) ?>)</span>
        <a class="btn" href="<?= e(APP_URL) ?>/">Dashboard</a>
        <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/index">Pacientes</a>
        <a class="btn" href="<?= e(APP_URL) ?>/?r=auth/logout">Sair</a>
      <?php else: ?>
        <a class="btn" href="<?= e(APP_URL) ?>/?r=auth/login">Entrar</a>
      <?php endif; ?>
    </div>
  </div>
