<h2>Nova Consulta — <?= e($p['name']) ?></h2>
<form method="post" action="<?= e(APP_URL) ?>/?r=visits/save">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="patient_id" value="<?= (int)$p['id'] ?>">

  <div class="grid">
    <div><label>Data/Hora</label><input type="datetime-local" name="visit_date" value="<?= e(date('Y-m-d\TH:i')) ?>"></div>
    <div><label>Queixa Principal (QP)</label><input name="complaint"></div>
    <div class="col-2"><label>HMA</label><textarea name="hpi"></textarea></div>
    <div class="col-2"><label>ISDA</label><textarea name="ros"></textarea></div>
    <div class="col-2"><label>Antecedentes Pessoais</label><textarea name="personal_history"></textarea></div>
    <div class="col-2"><label>Antecedentes Familiares</label><textarea name="family_history"></textarea></div>
    <div class="col-2"><label>Hábitos</label><textarea name="habits"></textarea></div>
    <div class="col-2"><label>Exame Físico</label><textarea name="physical_exam"></textarea></div>

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

    <div class="col-2"><label>Hipóteses/CID</label><textarea name="diagnosis" placeholder="Ex.: J00 (resfriado comum)"></textarea></div>
    <div class="col-2"><label>Plano/Conduta</label><textarea name="plan" placeholder="Exames, prescrições, orientações, retorno..."></textarea></div>
  </div>

  <div class="bar">
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/view&id=<?= (int)$p['id'] ?>">Cancelar</a>
    <button class="btn primary" type="submit">Salvar Consulta</button>
  </div>
</form>

<script>
function calcIMC(){
  const w = parseFloat(document.getElementById('weight_kg').value);
  const h = parseFloat(document.getElementById('height_m').value);
  const bmi = (!isNaN(w) && !isNaN(h) && h>0) ? (w/(h*h)) : '';
  document.getElementById('bmi').value = bmi ? bmi.toFixed(2) : '';
}
document.getElementById('weight_kg').addEventListener('input', calcIMC);
document.getElementById('height_m').addEventListener('input', calcIMC);
</script>
