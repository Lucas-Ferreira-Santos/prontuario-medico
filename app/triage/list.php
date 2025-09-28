<div class="bar">
  <h2>Fila de Triagem</h2>
  <form method="get" class="inline">
    <input type="hidden" name="r" value="triage/index">
    <input class="input" name="q" placeholder="Buscar por nome/CPF" value="<?= e($q ?? '') ?>">
    <select name="status" class="input" style="width:auto">
      <?php
        $opts = ['pendente'=>'Pendente','em_atendimento'=>'Em atendimento','finalizada'=>'Finalizada',''=>'Todos'];
        $cur = $status ?? 'pendente';
        foreach($opts as $k=>$v): ?>
          <option value="<?= e($k) ?>" <?= ($k===$cur?'selected':'') ?>><?= e($v) ?></option>
      <?php endforeach; ?>
    </select>
    <?php if (user_has_role(['medico','admin'])): ?>
      <label class="input" style="display:inline-flex;gap:.4rem;align-items:center">
        <input type="checkbox" name="meus" value="1" <?= !empty($onlyMine)?'checked':''; ?>> Meus atendimentos
      </label>
    <?php endif; ?>
    <button class="btn">Filtrar</button>
  </form>
  <div>
    <?php if (user_has_role(['enfermeira','secretaria','admin'])): ?>
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=patients/index">+ Triagem (escolha o paciente)</a>
    <?php endif; ?>
  </div>
</div>

<table class="table">
  <thead>
    <tr><th>Chegada</th><th>Paciente</th><th>Risco</th><th>Vitais</th><th>Status</th><th>Ações</th></tr>
  </thead>
  <tbody>
  <?php foreach($rows as $r):
      $risk=strtolower((string)$r['risk']);
      $colors=['vermelho'=>'#ef4444','laranja'=>'#f97316','amarelo'=>'#eab308','verde'=>'#22c55e'];
      $badge=$risk?'<span style="background:'.($colors[$risk]??'#444').';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>':'-';
  ?>
    <tr>
      <td><?= e(substr($r['triage_time'],11,5)) ?></td>
      <td><?= e($r['patient_name']) ?></td>
      <td><?= $badge ?></td>
      <td>PA <?= e($r['bp_systolic']) ?>/<?= e($r['bp_diastolic']) ?> · FC <?= e($r['hr']) ?> · FR <?= e($r['rr']) ?> · SpO₂ <?= e($r['spo2']) ?>% · TAX <?= e($r['tax']) ?>°C</td>
      <td>
        <?php if ($r['status']==='em_atendimento'): ?>
          Em atendimento <?= $r['medico_nome'] ? '— '.e($r['medico_nome']) : '' ?>
        <?php else: ?>
          <?= e(ucfirst($r['status'])) ?>
        <?php endif; ?>
      </td>
      <td>
        <a class="btn sm" href="<?= e(APP_URL) ?>/?r=triage/view&id=<?= (int)$r['id'] ?>">Ver</a>

        <?php if (user_has_role(['medico','admin'])): ?>
          <?php if ($r['status']==='pendente'): ?>
            <form method="post" action="<?= e(APP_URL) ?>/?r=triage/claim" class="inline">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <input type="hidden" name="patient_id" value="<?= (int)$r['patient_id'] ?>">
              <button class="btn sm primary">Atender</button>
            </form>
          <?php elseif ($r['status']==='em_atendimento' && (int)$r['taken_by'] === (int)(current_user()['id'] ?? 0)): ?>
            <form method="post" action="<?= e(APP_URL) ?>/?r=triage/release" class="inline">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <button class="btn sm">Devolver à fila</button>
            </form>
            <a class="btn sm" href="<?= e(APP_URL) ?>/?r=visits/form&patient_id=<?= (int)$r['patient_id'] ?>&triage_id=<?= (int)$r['id'] ?>">Abrir consulta</a>
          <?php endif; ?>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; if (empty($rows)) echo '<tr><td colspan="6">Sem itens.</td></tr>'; ?>
  </tbody>
</table>

<script>
  // auto-refresh da fila a cada 20s (pode ajustar)
  setTimeout(() => location.reload(), 20000);
</script>
