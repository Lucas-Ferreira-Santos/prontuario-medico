<div class="bar">
  <h2>Pacientes</h2>
  <div class="actions">
    <form method="get" class="inline">
      <input type="hidden" name="r" value="patients/index">
      <input class="input" type="text" name="q" placeholder="Buscar por nome ou CPF" value="<?= e($q) ?>">
      <button class="btn">Buscar</button>
    </form>
    <a class="btn primary" href="<?= e(APP_URL) ?>/?r=patients/form">+ Novo Paciente</a>
  </div>
</div>

<table class="table">
  <thead><tr><th>Nome</th><th>CPF</th><th>Nascimento</th><th>Telefone</th><th>Ações</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e($r['name']) ?></td>
      <td><?= e($r['cpf']) ?></td>
      <td><?= e($r['birthdate']) ?></td>
      <td><?= e($r['phone']) ?></td>
      <td>
        <a class="btn sm" href="<?= e(APP_URL) ?>/?r=patients/view&id=<?= (int)$r['id'] ?>">Abrir</a>
        <a class="btn sm" href="<?= e(APP_URL) ?>/?r=patients/form&id=<?= (int)$r['id'] ?>">Editar</a>
      </td>
    </tr>
  <?php endforeach; if (empty($rows)) echo '<tr><td colspan="5">Nenhum registro.</td></tr>'; ?>
  </tbody>
</table>
