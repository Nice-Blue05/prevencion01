<?php
require 'config.php';
$titulo = 'Panel';
$estados = ['recibido'=>'Recibido','en_revision'=>'En revisión','derivado'=>'Derivado','cerrado'=>'Cerrado'];
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok()) {
    $a = $_POST['accion'] ?? '';
    if ($a === 'login') {
        if (hash_equals(ADMIN_PASSWORD, $_POST['clave'] ?? '')) { $_SESSION['admin'] = true; session_regenerate_id(true); }
        else $err = 'Contraseña incorrecta.';
    } elseif ($a === 'logout') {
        unset($_SESSION['admin']);
    } elseif ($a === 'estado' && !empty($_SESSION['admin'])) {
        $nuevo = $_POST['estado'] ?? '';
        if (isset($estados[$nuevo])) {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('UPDATE reportes SET estado=? WHERE id=?')->execute([$nuevo, $id]);
            $r = db()->prepare('SELECT codigo,email,nombre FROM reportes WHERE id=?'); $r->execute([$id]); $r = $r->fetch();
            if ($r && $r['email']) {
                enviar_brevo($r['email'], (string)$r['nombre'], 'Actualización de tu caso '.$r['codigo'],
                    '<p>El estado de tu caso <strong>'.e($r['codigo']).'</strong> ahora es: <strong>'.e($estados[$nuevo]).'</strong>.</p>');
            }
        }
    }
    header('Location: admin.php'); exit;
}
include 'partials/header.php';
?>
<section class="wrap">
<?php if (empty($_SESSION['admin'])): ?>
  <div class="narrow">
    <h1>Panel del equipo</h1>
    <?php if ($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
    <form method="post" class="form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="accion" value="login">
      <label>Contraseña<input type="password" name="clave" required></label>
      <button class="btn" type="submit">Ingresar</button>
    </form>
  </div>
<?php else:
  $rows = db()->query('SELECT * FROM reportes ORDER BY creado_en DESC')->fetchAll();
  $total = count($rows);
  $psico = array_sum(array_column($rows, 'apoyo_psicologico'));
  $legal = array_sum(array_column($rows, 'apoyo_legal'));
?>
  <div class="bar-admin">
    <h1>Reportes</h1>
    <form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="accion" value="logout"><button class="btn ghost small">Salir</button></form>
  </div>
  <div class="grid stats">
    <div class="card"><h3><?= $total ?></h3><p>Reportes</p></div>
    <div class="card"><h3><?= $psico ?></h3><p>Piden apoyo psicológico</p></div>
    <div class="card"><h3><?= $legal ?></h3><p>Piden orientación legal</p></div>
  </div>
  <div class="tabla">
    <table>
      <thead><tr><th>Código</th><th>Fecha</th><th>Tipo</th><th>Zona</th><th>Descripción</th><th>Apoyo</th><th>Contacto</th><th>Estado</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= e($r['codigo']) ?></td>
          <td><?= e(date('d/m/Y H:i', strtotime($r['creado_en']))) ?></td>
          <td><?= e($r['tipo_violencia']) ?></td>
          <td><?= e($r['distrito']) ?></td>
          <td class="desc"><?= e($r['descripcion']) ?></td>
          <td><?= $r['apoyo_psicologico']?'🧠 ':'' ?><?= $r['apoyo_legal']?'⚖️':'' ?></td>
          <td><?= $r['anonimo'] ? 'Anónimo' : e($r['nombre']) ?><br><small><?= e($r['email']) ?> <?= e($r['telefono']) ?></small></td>
          <td>
            <form method="post" class="inline">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="accion" value="estado">
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <select name="estado" onchange="this.form.submit()">
                <?php foreach ($estados as $k=>$l) echo '<option value="'.$k.'"'.($r['estado']===$k?' selected':'').'>'.e($l).'</option>'; ?>
              </select>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
</section>
<?php include 'partials/footer.php'; ?>
