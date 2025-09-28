<div class="bar">
  <h2>Fila de Triagem</h2>
  <div>
    <form method="get" class="inline">
      <input type="hidden" name="r" value="triage/index">
      <input class="input" name="q" placeholder="Buscar por nome/CPF" value="<?= e($q ?? '') ?>">
      <select name="status" class="input" style="width:auto">
        <?php $sts = [''=>'Todos','aberto'=>'Abertos','encaminhado'=>'Encaminhados','atendido'=>'Atendidos']; 
        $cur = $status ?? ''; foreach($sts as $k=>$v): ?>
          <option value="<?= e($k) ?>" <?= ($k===$cur?'selected':'') ?>><?= e($v) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn">Filtrar</button>
    </form>
  </div>
</div>

<table class="table">
  <thead><tr><th>Risco</th><th>Hora</th><th>Paciente</th><th>Motivo</th><th>Vitais</th><th>Ações</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r):
    $risk=strtolower((string)$r['risk']);
    $colors=['verde'=>'#22c55e','amarelo'=>'#eab308','laranja'=>'#f97316','vermelho'=>'#ef4444'];
    $badge=$risk?'<span style="background:'.($colors[$risk]??'#444').';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>':'';
  ?>
    <tr>
      <td><?= $badge ?></td>
      <td><?= e(substr($r['triage_time'],11,5)) ?></td>
      <td><?= e($r['name']) ?></td>
      <td><?= e(mb_strimwidth($r['motive']??'',0,40,'…')) ?></td>
      <td>PA <?= e($r['bp_systolic']) ?>/<?= e($r['bp_diastolic']) ?>, FC <?= e($r['hr']) ?>, FR <?= e($r['rr']) ?>, SpO₂ <?= e($r['spo2']) ?>%, TAX <?= e($r['tax']) ?>°C</td>
      <td>
        <a class="btn sm" href="<?= e(APP_URL) ?>/?r=triage/view&id=<?= (int)$r['id'] ?>">Ver</a>
        <a class="btn sm primary" href="<?= e(APP_URL) ?>/?r=visits/form&patient_id=<?= (int)$r['patient_id'] ?>&triage_id=<?= (int)$r['id'] ?>">Atender</a>
      </td>
    </tr>
  <?php endforeach; if (empty($rows)) echo '<tr><td colspan="6">Sem triagens.</td></tr>'; ?>
  </tbody>
</table>
