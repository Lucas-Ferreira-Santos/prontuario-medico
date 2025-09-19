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

  <h3>1) História Clínica</h3>
  <p><b>Queixa principal (QP):</b> <?= nl2br(e($v['complaint'])) ?></p>
  <p><b>HMA:</b> <?= nl2br(e($v['hpi'])) ?></p>
  <p><b>ISDA:</b> <?= nl2br(e($v['ros'])) ?></p>
  <p><b>Antecedentes pessoais:</b> <?= nl2br(e($v['personal_history'])) ?></p>
  <p><b>Antecedentes familiares:</b> <?= nl2br(e($v['family_history'])) ?></p>
  <p><b>Hábitos:</b> <?= nl2br(e($v['habits'])) ?></p>

  <h3>2) Exame Físico</h3>
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
  <p><b>Exame físico:</b><br><?= nl2br(e($v['physical_exam'])) ?></p>

  <h3>3) Hipóteses / CID</h3>
  <p><?= nl2br(e($v['diagnosis'])) ?></p>

  <h3>4) Plano / Conduta</h3>
  <p><?= nl2br(e($v['plan'])) ?></p>

  <br><br>
  <p>_________________________________________<br>
  Assinatura e carimbo do profissional</p>
</div>
