<?php $is_edit = !empty($data['id']); ?>
<h2><?= $is_edit ? 'Editar' : 'Novo' ?> Paciente</h2>
<form method="post" action="<?= e(APP_URL) ?>/?r=patients/save">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <?php if ($is_edit): ?><input type="hidden" name="id" value="<?= (int)$data['id'] ?>"><?php endif; ?>

  <div class="grid">
    <div><label>Nome*</label><input name="name" required value="<?= e($data['name']) ?>"></div>
    <div><label>CPF</label><input name="cpf" value="<?= e($data['cpf']) ?>"></div>
    <div><label>Nascimento</label><input type="date" name="birthdate" value="<?= e($data['birthdate']) ?>"></div>
    <div>
      <label>Sexo</label>
      <select name="sex">
        <option value="">-</option>
        <?php foreach (['F','M','Outro'] as $s): ?>
          <option <?= ($data['sex']===$s)?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>Estado civil</label><input name="marital_status" value="<?= e($data['marital_status']) ?>"></div>
    <div><label>Telefone</label><input name="phone" value="<?= e($data['phone']) ?>"></div>
    <div><label>E-mail</label><input type="email" name="email" value="<?= e($data['email']) ?>"></div>
    <div class="col-2"><label>Endereço</label><input name="address" value="<?= e($data['address']) ?>"></div>
    <div><label>Cidade</label><input name="city" value="<?= e($data['city']) ?>"></div>
    <div><label>UF</label><input name="uf" maxlength="2" value="<?= e($data['uf']) ?>"></div>
    <div><label>CEP</label><input name="cep" value="<?= e($data['cep']) ?>"></div>
    <div><label>Contato de Emergência</label><input name="emergency_contact" value="<?= e($data['emergency_contact']) ?>"></div>
    <div><label>Telefone Emergência</label><input name="emergency_phone" value="<?= e($data['emergency_phone']) ?>"></div>
    <div class="col-2"><label>Alergias</label><input name="allergies" value="<?= e($data['allergies']) ?>"></div>
    <div class="col-2"><label>Comorbidades</label><input name="comorbidities" value="<?= e($data['comorbidities']) ?>"></div>
  </div>

  <div class="bar">
    <a class="btn" href="<?= e(APP_URL) ?>/?r=patients/index">Voltar</a>
    <button class="btn primary" type="submit">Salvar</button>
  </div>
</form>
