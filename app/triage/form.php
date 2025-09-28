<h2>Nova Triagem — <?= e($p['name']) ?></h2>

<form method="post" action="<?= e(APP_URL) ?>/?r=triage/save">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="patient_id" value="<?= (int)$p['id'] ?>">
  <input type="hidden" name="triage_time" value="<?= e(date('Y-m-d H:i:s')) ?>">

  <div class="grid">
    <div class="col-2"><label>Motivo da Consulta</label><input name="motive" required></div>
    <div class="col-2"><label>Comorbidades</label><textarea name="comorbidities"><?= e($p['comorbidities'] ?? '') ?></textarea></div>
    <div class="col-2"><label>Medicações em uso/de uso contínuo</label><textarea name="meds"></textarea></div>
    <div class="col-2"><label>Alergia medicamentosa</label><input name="allergy" value="<?= e($p['allergies'] ?? '') ?>"></div>

    <div><label>PA (sist.)</label><input type="number" name="bp_systolic" min="0"></div>
    <div><label>PA (diast.)</label><input type="number" name="bp_diastolic" min="0"></div>
    <div><label>FC (bpm)</label><input type="number" name="hr" min="0"></div>
    <div><label>FR (irpm)</label><input type="number" name="rr" min="0"></div>
    <div><label>SpO₂ (%)</label><input type="number" name="spo2" min="0" max="100"></div>
    <div><label>DXA (0–10)</label><input type="number" name="dxa" min="0" max="10"></div>
    <div><label>TAX (°C)</label><input type="number" step="0.1" name="tax"></div>

    <div><label>Peso (kg)</label><input type="number" step="0.1" name="weight_kg" id="t_weight"></div>
    <div><label>Altura (m)</label><input type="number" step="0.01" name="height_m" id="t_height"></div>
    <div><label>IMC</label><input type="number" step="0.01" name="bmi" id="t_bmi" readonly></div>

    <div class="col-2">
      <label>Classificação de Risco</label>
      <select name="risk" required>
        <option value="">-</option>
        <option value="verde">Verde</option>
        <option value="amarelo">Amarelo</option>
        <option value="laranja">Laranja</option>
        <option value="vermelho">Vermelho</option>
      </select>
    </div>
    <div class="col-2"><label>Observações</label><textarea name="notes"></textarea></div>
  </div>

  <div class="bar">
    <a class="btn" href="<?= e(APP_URL) ?>/?r=triage/index">Voltar</a>
    <button class="btn primary" type="submit">Salvar e encaminhar</button>
  </div>
</form>

<script>
function calcTBMI(){
  const w = parseFloat(document.getElementById('t_weight').value);
  const h = parseFloat(document.getElementById('t_height').value);
  const bmi = (!isNaN(w)&&!isNaN(h)&&h>0) ? (w/(h*h)) : '';
  document.getElementById('t_bmi').value = bmi ? bmi.toFixed(2) : '';
}
document.getElementById('t_weight').addEventListener('input', calcTBMI);
document.getElementById('t_height').addEventListener('input', calcTBMI);
</script>
