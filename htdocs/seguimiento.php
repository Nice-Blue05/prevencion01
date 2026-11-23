<?php
require 'config.php';
$titulo = 'Seguimiento';
$caso = null; $msg = '';
$etapas = ['recibido'=>'Recibido','en_revision'=>'En revisión','derivado'=>'Derivado a apoyo','cerrado'=>'Cerrado'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { $msg = 'La sesión expiró. Recarga la página.'; }
    else {
        $cod = strtoupper(trim($_POST['codigo'] ?? ''));
        $st = db()->prepare('SELECT codigo, estado, tipo_violencia, creado_en FROM reportes WHERE codigo = ?');
        $st->execute([$cod]);
        $caso = $st->fetch();
        if (!$caso) $msg = 'No encontramos ese código. Revisa que esté bien escrito.';
    }
}
include 'partials/header.php';
?>
<section class="wrap narrow">
  <h1>Seguimiento de tu caso</h1>
  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Código de seguimiento
      <input type="text" name="codigo" placeholder="VS-XXXXXX" required maxlength="12">
    </label>
    <button class="btn" type="submit">Consultar</button>
  </form>

  <?php if ($msg): ?><div class="alert"><?= e($msg) ?></div><?php endif; ?>

  <?php if ($caso): ?>
    <div class="card">
      <h3>Caso <?= e($caso['codigo']) ?></h3>
      <p class="muted">Tipo: <?= e($caso['tipo_violencia']) ?> · Registrado el <?= e(date('d/m/Y', strtotime($caso['creado_en']))) ?></p>
      <ul class="timeline">
        <?php $alcanzado = true; foreach ($etapas as $k => $label):
          $cls = $alcanzado ? 'done' : '';
          if ($k === $caso['estado']) { $cls .= ' actual'; $alcanzado = false; } ?>
          <li class="<?= $cls ?>"><?= e($label) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</section>
<?php include 'partials/footer.php'; ?>
