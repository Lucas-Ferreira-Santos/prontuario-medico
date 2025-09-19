<div class="card">
  <h2>Dashboard</h2>
  <p>Bem-vindo ao <?= e(APP_NAME) ?>. Comece cadastrando um paciente.</p>
  <div>
    <a class="btn primary" href="<?= e(APP_URL) ?>/?r=patients/form">+ Novo Paciente</a>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/index">Ver Pacientes</a>
  </div>
</div>
