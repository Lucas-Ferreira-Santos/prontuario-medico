<?php
// Espera $t com:
// patient_name, patient_id, id (triage), triage_time, risk,
// bp_systolic, bp_diastolic, hr, rr, spo2, tax (temp),
// weight_kg, height_m, bmi,
// reason, comorbidities, meds, drug_allergy (se existirem)
?>

<div class="bar">
  <h2>Triagem — <?= e($t['patient_name']) ?></h2>
  <div>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=triage/index">Voltar</a>
    <?php if (user_has_role(['medico','admin'])): ?>
      <a class="btn primary" href="<?= e(APP_URL) ?>/?r=visits/form&patient_id=<?= (int)$t['patient_id'] ?>&triage_id=<?= (int)$t['id'] ?>">
        Iniciar consulta
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="card">
  <p><strong>Hora:</strong> <?= e($t['triage_time']) ?></p>
  <?php if (!empty($t['risk'])): ?>
    <p><strong>Classificação de risco:</strong> <span class="badge"><?= e($t['risk']) ?></span></p>
  <?php endif; ?>
</div>

<div class="grid g2">
  <div class="card">
    <h3>Dados clínicos</h3>
    <?php if (!empty($t['reason'])): ?>
      <p><strong>Motivo da consulta:</strong> <?= nl2br(e($t['reason'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($t['comorbidities'])): ?>
      <p><strong>Comorbidades:</strong> <?= nl2br(e($t['comorbidities'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($t['meds'])): ?>
      <p><strong>Medicações em uso:</strong> <?= nl2br(e($t['meds'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($t['drug_allergy'])): ?>
      <p><strong>Alergia medicamentosa:</strong> <?= nl2br(e($t['drug_allergy'])) ?></p>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>Sinais vitais</h3>
    <p>
      <strong>PA:</strong> <?= e($t['bp_systolic'] ?? '') ?>/<?= e($t['bp_diastolic'] ?? '') ?> |
      <strong>FC:</strong> <?= e($t['hr'] ?? '') ?> |
      <strong>FR:</strong> <?= e($t['rr'] ?? '') ?> |
      <strong>SpO₂:</strong> <?= e($t['spo2'] ?? '') ?>% |
      <strong>TAX:</strong> <?= e($t['tax'] ?? '') ?> °C
    </p>
    <p>
      <strong>Peso:</strong> <?= e($t['weight_kg'] ?? '') ?> kg |
      <strong>Altura:</strong> <?= e($t['height_m'] ?? '') ?> m |
      <strong>IMC:</strong> <?= e($t['bmi'] ?? '') ?>
    </p>
  </div>
</div>
