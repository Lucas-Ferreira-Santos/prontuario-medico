<div class="bar print-hidden">
  <h2>Consulta de <?= e($v['patient_name']) ?></h2>
  <div>
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/view&id=<?= (int)$v['patient_id'] ?>">Voltar</a>
    <button class="btn" onclick="window.print()">Imprimir</button>
  </div>
</div>

<div class="card print-card">
  <h2><?= e(APP_NAME) ?></h2>
  <p><b>Paciente:</b> <?= e($v['patient_name']) ?> — <b>CPF:</b> <?= e($v['cpf']) ?></p>
  <p><b>Data/Hora:</b> <?= e($v['visit_date']) ?></p>
  <?php if (!empty($v['triage_motive'])):
  $risk = strtolower((string)$v['triage_risk']);
  $colors = ['verde'=>'#22c55e','amarelo'=>'#eab308','laranja'=>'#f97316','vermelho'=>'#ef4444'];
  $badge = $risk
  ? '<span style="background:'.(($colors[$risk] ?? '#444')).';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>'
  : '';

?>
  <h3>Resumo da Triagem</h3>
  <p><b>Hora:</b> <?= e($v['triage_time']) ?> <?= $badge ?></p>
  <p><b>Motivo:</b> <?= nl2br(e($v['triage_motive'])) ?></p>
  <p><b>Vitais (triagem):</b>
    PA <?= e($v['t_bp_s']) ?>/<?= e($v['t_bp_d']) ?> —
    FC <?= e($v['t_hr']) ?> —
    FR <?= e($v['t_rr']) ?> —
    SpO₂ <?= e($v['t_spo2']) ?>% —
    TAX <?= e($v['t_tax']) ?>°C —
    Peso <?= e($v['t_w']) ?> kg —
    Altura <?= e($v['t_h']) ?> m —
    IMC <?= e($v['t_bmi']) ?>
  </p>
<?php endif; ?>


  <!-- ===================== TRIAGEM ===================== -->
  <h3>Triagem</h3>
  <?php
    $risk = strtolower(trim((string)($v['triage_risk'] ?? '')));
    $colors = ['verde'=>'#22c55e','amarelo'=>'#eab308','laranja'=>'#f97316','vermelho'=>'#ef4444'];
    $badge = $risk
  ? '<span style="background:'.(($colors[$risk] ?? '#444')).';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>'
  : '';

  ?>
  <p><b>Motivo:</b> <?= nl2br(e($v['triage_motive'])) ?> <?= $badge ?></p>
  <p><b>Comorbidades:</b> <?= nl2br(e($v['triage_comorbidities'])) ?></p>
  <p><b>Medicações em uso:</b> <?= nl2br(e($v['triage_meds'])) ?></p>
  <p><b>Alergia medicamentosa:</b> <?= nl2br(e($v['triage_allergy'])) ?></p>
  <p>
    <b>PA (triagem):</b> <?= e($v['triage_bp_systolic']) ?>/<?= e($v['triage_bp_diastolic']) ?> mmHg —
    <b>FC:</b> <?= e($v['triage_hr']) ?> bpm —
    <b>FR:</b> <?= e($v['triage_rr']) ?> irpm —
    <b>SpO₂:</b> <?= e($v['triage_spo2']) ?>% —
    <b>DXA:</b> <?= e($v['triage_dxa']) ?> —
    <b>TAX:</b> <?= e($v['triage_tax']) ?> °C —
    <b>Peso:</b> <?= e($v['triage_weight_kg']) ?> kg —
    <b>Altura:</b> <?= e($v['triage_height_m']) ?> m —
    <b>IMC:</b> <?= e($v['triage_bmi']) ?>
  </p>

  <!-- ===================== MÉDICO ===================== -->
  <h3>Anamnese</h3>
  <p><b>Queixa Principal:</b> <?= nl2br(e($v['complaint'])) ?></p>
  <p><b>HMA:</b> <?= nl2br(e($v['hpi'])) ?></p>
  <p><b>ISDA:</b> <?= nl2br(e($v['ros'])) ?></p>

  <h3>Exame Físico</h3>
  <p><b>PA:</b> <?= e($v['bp_systolic']) ?>/<?= e($v['bp_diastolic']) ?> mmHg —
     <b>FC:</b> <?= e($v['hr']) ?> bpm —
     <b>FR:</b> <?= e($v['rr']) ?> irpm —
     <b>Temp:</b> <?= e($v['temp_c']) ?> °C —
     <b>SpO₂:</b> <?= e($v['spo2']) ?>% —
     <b>Peso:</b> <?= e($v['weight_kg']) ?> kg —
     <b>Altura:</b> <?= e($v['height_m']) ?> m —
     <b>IMC:</b> <?= e($v['bmi']) ?> —
     <b>Glicemia:</b> <?= e($v['capillary_glucose']) ?>
  </p>
  <p><?= nl2br(e($v['physical_exam'])) ?></p>

  <h3>Hipótese Diagnóstica</h3>
  <p><?= nl2br(e($v['diagnosis'])) ?></p>

  <h3>Exames</h3>
  <p><?= nl2br(e($v['tests'])) ?></p>

  <h3>Conduta</h3>
  <p><?= nl2br(e($v['plan'])) ?></p>

  <h3>Reavaliação</h3>
  <p><?= nl2br(e($v['reassessment'])) ?></p>

  <br><br>
  <p>_________________________________________<br>
  Assinatura e carimbo do profissional</p>
</div>
