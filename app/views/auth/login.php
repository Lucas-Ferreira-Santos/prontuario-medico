<div class="card" style="max-width:420px;margin:40px auto">
  <h2>Entrar</h2>
  <?php if (!empty($error)): ?>
    <div class="card" style="background:#3a1216;border-color:#6d1f26"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= e(APP_URL) ?>/?r=auth/login">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>E-mail</label>
    <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    <label>Senha</label>
    <input type="password" name="password" required>
    <button class="btn primary" type="submit">Entrar</button>
    <p class="muted">Admin padrão: admin@local / admin123</p>
  </form>
</div>
