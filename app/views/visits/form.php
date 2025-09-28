<h2>Nova Consulta — <?= e($p['name']) ?></h2>
<form method="post" action="<?= e(APP_URL) ?>/?r=visits/save">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="patient_id" value="<?= (int)$p['id'] ?>">
  <?php if (!empty($triage)): 
    $risk=strtolower((string)$triage['risk']);
    $colors=['verde'=>'#22c55e','amarelo'=>'#eab308','laranja'=>'#f97316','vermelho'=>'#ef4444'];
    $badge=$risk?'<span style="background:'.($colors[$risk]??'#444').';padding:2px 8px;border-radius:8px;color:#000;font-weight:700">'.e($risk).'</span>':'';
  ?>
    <input type="hidden" name="triage_id" value="<?= (int)$triage['id'] ?>">
    <div class="card">
      <div class="bar">
        <h3>Triagem — <?= $badge ?> — <?= e(substr($triage['triage_time'],0,16)) ?></h3>
        <button class="btn sm" type="button" onclick="copiarTriagem()">Copiar dados da triagem</button>
      </div>
      <div class="grid">
        <div class="col-2"><b>Motivo:</b> <?= e($triage['motive']) ?></div>
        <div><b>PA:</b> <?= e($triage['bp_systolic']) ?>/<?= e($triage['bp_diastolic']) ?></div>
        <div><b>FC:</b> <?= e($triage['hr']) ?></div>
        <div><b>FR:</b> <?= e($triage['rr']) ?></div>
        <div><b>SpO₂:</b> <?= e($triage['spo2']) ?>%</div>
        <div><b>TAX:</b> <?= e($triage['tax']) ?>°C</div>
        <div><b>Peso/Alt/IMC:</b> <?= e($triage['weight_kg']) ?> kg · <?= e($triage['height_m']) ?> m · <?= e($triage['bmi']) ?></div>
      </div>
    </div>
  <?php endif; ?>



  

  <!-- ===================== ANAMNESE/EXAME/PLANO ===================== -->
  <div class="grid">
    <div><label>Data/Hora</label><input type="datetime-local" name="visit_date" value="<?= e(date('Y-m-d\TH:i')) ?>"></div>
    <div><label>Queixa Principal (QP) / Motivo</label><input name="complaint"></div>

    <div class="col-2"><label>Anamnese (HMA)</label><textarea name="hpi"></textarea></div>
    <div class="col-2"><label>ISDA (Revisão de Sistemas)</label><textarea name="ros"></textarea></div>

    <div class="col-2"><label>Antecedentes Pessoais</label><textarea name="personal_history"></textarea></div>
    <div class="col-2"><label>Antecedentes Familiares</label><textarea name="family_history"></textarea></div>
    <div class="col-2"><label>Hábitos</label><textarea name="habits"></textarea></div>

    <div class="col-2"><label>Exame Físico (descrição)</label><textarea name="physical_exam"></textarea></div>

    <!-- Sinais vitais do médico -->
    <div><label>PA (sist.)</label><input type="number" name="bp_systolic" min="0"></div>
    <div><label>PA (diast.)</label><input type="number" name="bp_diastolic" min="0"></div>
    <div><label>FC (bpm)</label><input type="number" name="hr" min="0"></div>
    <div><label>FR (irpm)</label><input type="number" name="rr" min="0"></div>
    <div><label>Temp (°C)</label><input type="number" step="0.1" name="temp_c"></div>
    <div><label>SpO₂ (%)</label><input type="number" name="spo2" min="0" max="100"></div>

    <div><label>Peso (kg)</label><input type="number" step="0.1" name="weight_kg" id="weight_kg"></div>
    <div><label>Altura (m)</label><input type="number" step="0.01" name="height_m" id="height_m"></div>
    <div><label>IMC</label><input type="number" step="0.01" name="bmi" id="bmi" readonly></div>

    <div><label>Glicemia capilar</label><input type="number" step="0.1" name="capillary_glucose"></div>

    <div class="col-2"><label>Hipótese Diagnóstica / CID</label><textarea name="diagnosis"></textarea></div>
    <div class="col-2"><label>Exames</label><textarea name="tests" placeholder="Ex.: hemograma, PCR, Rx..."></textarea></div>
    <div class="col-2"><label>Conduta / Plano</label><textarea name="plan" placeholder="prescrições, orientações, retorno..."></textarea></div>
    <div class="col-2"><label>Reavaliação</label><textarea name="reassessment" placeholder="evolução, reexame, decisão final..."></textarea></div>
  </div>

  <div class="bar">
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/view&id=<?= (int)$p['id'] ?>">Cancelar</a>
    <button class="btn primary" type="submit">Salvar Consulta</button>
  </div>
</form>

<script>
// IMC TRIAGEM
function calcTriageIMC(){
  const w = parseFloat(document.getElementById('triage_weight').value);
  const h = parseFloat(document.getElementById('triage_height').value);
  const bmi = (!isNaN(w) && !isNaN(h) && h>0) ? (w/(h*h)) : '';
  document.getElementById('triage_bmi').value = bmi ? bmi.toFixed(2) : '';
}
document.getElementById('triage_weight').addEventListener('input', calcTriageIMC);
document.getElementById('triage_height').addEventListener('input', calcTriageIMC);

// IMC MÉDICO
function calcIMC(){
  const w = parseFloat(document.getElementById('weight_kg').value);
  const h = parseFloat(document.getElementById('height_m').value);
  const bmi = (!isNaN(w) && !isNaN(h) && h>0) ? (w/(h*h)) : '';
  document.getElementById('bmi').value = bmi ? bmi.toFixed(2) : '';
}
document.getElementById('weight_kg').addEventListener('input', calcIMC);
document.getElementById('height_m').addEventListener('input', calcIMC);
</script>
<script>
function copiarTriagem(){
  <?php if (!empty($triage)): ?>
  const map = {
    'bp_systolic': '<?= (int)$triage['bp_systolic'] ?>',
    'bp_diastolic': '<?= (int)$triage['bp_diastolic'] ?>',
    'hr': '<?= (int)$triage['hr'] ?>',
    'rr': '<?= (int)$triage['rr'] ?>',
    'spo2': '<?= (int)$triage['spo2'] ?>',
    'temp_c': '<?= htmlspecialchars((string)$triage['tax'], ENT_QUOTES) ?>',
    'weight_kg': '<?= htmlspecialchars((string)$triage['weight_kg'], ENT_QUOTES) ?>',
    'height_m': '<?= htmlspecialchars((string)$triage['height_m'], ENT_QUOTES) ?>',
    'bmi': '<?= htmlspecialchars((string)$triage['bmi'], ENT_QUOTES) ?>',
    'complaint': '<?= htmlspecialchars((string)$triage['motive'], ENT_QUOTES) ?>'
  };
  for (const id in map){
    const el = document.querySelector(`[name="${id}"]`);
    if (el) el.value = map[id];
  }
  <?php endif; ?>
}
</script>
<script>
function copiarTriagem(){
  <?php if (!empty($triage)): ?>
  const map = {
    'complaint': '<?= htmlspecialchars((string)$triage['motive'], ENT_QUOTES) ?>',
    'bp_systolic': '<?= (int)$triage['bp_systolic'] ?>',
    'bp_diastolic': '<?= (int)$triage['bp_diastolic'] ?>',
    'hr': '<?= (int)$triage['hr'] ?>',
    'rr': '<?= (int)$triage['rr'] ?>',
    'spo2': '<?= (int)$triage['spo2'] ?>',
    'temp_c': '<?= htmlspecialchars((string)$triage['tax'], ENT_QUOTES) ?>',
    'weight_kg': '<?= htmlspecialchars((string)$triage['weight_kg'], ENT_QUOTES) ?>',
    'height_m': '<?= htmlspecialchars((string)$triage['height_m'], ENT_QUOTES) ?>',
    'bmi': '<?= htmlspecialchars((string)$triage['bmi'], ENT_QUOTES) ?>'
  };
  for (const k in map){ const el = document.querySelector(`[name="${k}"]`); if (el) el.value = map[k]; }
  <?php endif; ?>
}
</script>
