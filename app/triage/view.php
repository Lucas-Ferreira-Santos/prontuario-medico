<?php
$risk = strtolower((string)$t['risk']);
$colors = ['verde'=>'#22c55e','amarelo'=>'#eab308','laranja'=>'#f97316','vermelho'=>'#ef4444'];
$badge = $risk ? '<span style="background:'.$colors[$risk]??'#444'.';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>' : '';
?>
<div class="bar">
  <h2>Triagem — <?= e($t['name']) ?></h2>
  <div>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=triage/index">Voltar</a>
    <a class="btn primary" href="<?= e(APP_URL) ?>/?r=visits/form&patient_id=<?= (int)$t['patient_id'] ?>&triage_id=<?= (int)$t['id'] ?>">Iniciar consulta</a>
  </div>
</div>

<div class="card">
  <p><b>Hora:</b> <?= e($t['triage_time']) ?> <?= $badge ?></p>
  <p><b>Motivo:</b> <?= nl2br(e($t['motive'])) ?></p>
  <p><b>Comorbidades:</b> <?= nl2br(e($t['comorbidities'])) ?></p>
  <p><b>Medicações em uso:</b> <?= nl2br(e($t['meds'])) ?></p>
  <p><b>Alergia medicamentosa:</b> <?= nl2br(e($t['allergy'])) ?></p>
  <p>
    <b>PA:</b> <?= e($t['bp_systolic']) ?>/<?= e($t['bp_diastolic']) ?> —
    <b>FC:</b> <?= e($t['hr']) ?> —
    <b>FR:</b> <?= e($t['rr']) ?> —
    <b>SpO₂:</b> <?= e($t['spo2']) ?>% —
    <b>DXA:</b> <?= e($t['dxa']) ?> —
    <b>TAX:</b> <?= e($t['tax']) ?> °C —
    <b>Peso:</b> <?= e($t['weight_kg']) ?> kg —
    <b>Altura:</b> <?= e($t['height_m']) ?> m —
    <b>IMC:</b> <?= e($t['bmi']) ?>
  </p>
  <?php if (!empty($t['notes'])): ?>
  <p><b>Observações:</b> <?= nl2br(e($t['notes'])) ?></p>
  <?php endif; ?>
</div>
