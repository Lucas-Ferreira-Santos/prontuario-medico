<div class="bar">
  <h2><?= e($p['name']) ?></h2>
  <div>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/index">Voltar</a>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/form&id=<?= (int)$p['id'] ?>">Editar</a>

    <?php if (user_has_role(['recepcao','admin'])): ?>
      <!-- Recepção cria a triagem -->
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=triage/form&patient_id=<?= (int)$p['id'] ?>">+ Nova Triagem</a>
    <?php endif; ?>

    <?php if (user_has_role(['medico','admin'])): ?><div class="bar">
  <h2><?= e($p['name']) ?></h2>
  <div>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/index">Voltar</a>
    <?php if (user_has_role(['enfermeira','secretaria','admin'])): ?>
      <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/form&id=<?= (int)$p['id'] ?>">Editar</a>
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=triage/form&patient_id=<?= (int)$p['id'] ?>">+ Nova Triagem</a>
    <?php endif; ?>
    <?php if (user_has_role(['medico','admin'])): ?>
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=triage/index&status=pendente">Fila Pendente</a>
    <?php endif; ?>
  </div>
</div>

      <!-- Médico inicia a consulta; se quiser forçar usar triagem, veja nota abaixo -->
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=visits/form&patient_id=<?= (int)$p['id'] ?>">+ Nova Consulta</a>
    <?php endif; ?>
  </div>
</div>


<div class="card">
  <h3>Dados</h3>
  <div class="grid">
    <div><b>CPF:</b> <?= e($p['cpf']) ?></div>
    <div><b>Nasc.:</b> <?= e($p['birthdate']) ?></div>
    <div><b>Sexo:</b> <?= e($p['sex']) ?></div>
    <div><b>Estado civil:</b> <?= e($p['marital_status']) ?></div>
    <div><b>Telefone:</b> <?= e($p['phone']) ?></div>
    <div><b>E-mail:</b> <?= e($p['email']) ?></div>
    <div class="col-2"><b>Endereço:</b> <?= e($p['address']) ?>, <?= e($p['city']) ?>/<?= e($p['uf']) ?> - <?= e($p['cep']) ?></div>
    <div><b>Contato emergência:</b> <?= e($p['emergency_contact']) ?></div>
    <div><b>Fone emergência:</b> <?= e($p['emergency_phone']) ?></div>
    <div class="col-2"><b>Alergias:</b> <?= e($p['allergies']) ?></div>
    <div class="col-2"><b>Comorbidades:</b> <?= e($p['comorbidities']) ?></div>
  </div>
</div>

<div class="card">
  <h3>Consultas</h3>
  <table class="table">
    <thead><tr><th>Data</th><th>Queixa</th><th>PA</th><th>FC</th><th>Temp</th><th>SpO₂</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($visits as $row): ?>
      <tr>
        <td><?= e(substr($row['visit_date'],0,16)) ?></td>
        <td><?= e(mb_strimwidth($row['complaint'] ?? '',0,50,'…')) ?></td>
        <td><?= e($row['bp_systolic']) ?>/<?= e($row['bp_diastolic']) ?></td>
        <td><?= e($row['hr']) ?></td>
        <td><?= e($row['temp_c']) ?></td>
        <td><?= e($row['spo2']) ?>%</td>
        <td><a class="btn sm" href="<?= e(APP_URL) ?>/?r=visits/view&id=<?= (int)$row['id'] ?>">Abrir</a></td>
      </tr>
    <?php endforeach; if (empty($visits)) echo '<tr><td colspan="7">Sem consultas cadastradas.</td></tr>'; ?>
    </tbody>
  </table>
</div>
