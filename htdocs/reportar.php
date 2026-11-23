<?php
require 'config.php';
$titulo = 'Reportar';

$tipos = ['Física','Psicológica','Sexual','Económica o patrimonial','Acoso digital','Acoso en la calle o el trabajo','Otra'];
$v = ['tipo'=>'','descripcion'=>'','fecha'=>'','distrito'=>'','anonimo'=>'1','nombre'=>'','email'=>'','telefono'=>'','psico'=>'','legal'=>''];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) $errores[] = 'La sesión expiró. Recarga la página e inténtalo de nuevo.';

    $v['tipo']        = trim($_POST['tipo'] ?? '');
    $v['descripcion'] = trim($_POST['descripcion'] ?? '');
    $v['fecha']       = trim($_POST['fecha'] ?? '');
    $v['distrito']    = trim($_POST['distrito'] ?? '');
    $v['anonimo']     = isset($_POST['anonimo']) ? '1' : '';
    $v['nombre']      = $v['anonimo'] ? '' : trim($_POST['nombre'] ?? '');
    $v['email']       = trim($_POST['email'] ?? '');
    $v['telefono']    = trim($_POST['telefono'] ?? '');
    $v['psico']       = isset($_POST['psico']) ? '1' : '';
    $v['legal']       = isset($_POST['legal']) ? '1' : '';

    if (!in_array($v['tipo'], $tipos, true)) $errores[] = 'Selecciona el tipo de violencia.';
    if (mb_strlen($v['descripcion']) < 20) $errores[] = 'Describe lo ocurrido con al menos 20 caracteres.';
    if ($v['distrito'] === '') $errores[] = 'Indica el distrito o zona.';
    if ($v['fecha'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $v['fecha'])) $errores[] = 'Fecha no válida.';
    if ($v['email'] !== '' && !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'El correo no es válido.';
    if (!isset($_POST['consentimiento'])) $errores[] = 'Debes aceptar el tratamiento confidencial de tus datos.';

    if (!$errores) {
        $codigo = 'VS-' . strtoupper(bin2hex(random_bytes(3)));
        $st = db()->prepare('INSERT INTO reportes
            (codigo,tipo_violencia,descripcion,fecha_hecho,distrito,anonimo,nombre,email,telefono,apoyo_psicologico,apoyo_legal)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([
            $codigo, $v['tipo'], $v['descripcion'], $v['fecha'] ?: null, $v['distrito'],
            $v['anonimo'] ? 1 : 0, $v['nombre'] ?: null, $v['email'] ?: null, $v['telefono'] ?: null,
            $v['psico'] ? 1 : 0, $v['legal'] ? 1 : 0,
        ]);

        // Correos con Brevo (si fallan, el reporte igual queda guardado)
        if ($v['email']) {
            enviar_brevo($v['email'], $v['nombre'], 'Recibimos tu reporte · '.$codigo,
                '<h2>Tu reporte fue recibido</h2><p>Tu código de seguimiento es <strong>'.e($codigo).'</strong>.</p>'
               .'<p>Puedes consultar el estado en cualquier momento con ese código. Si estás en peligro, llama al 105 o a la Línea 100.</p>');
        }
        enviar_brevo(NOTIFY_EMAIL, 'Equipo', 'Nuevo reporte '.$codigo,
            '<p>Nuevo reporte <strong>'.e($codigo).'</strong> · Tipo: '.e($v['tipo']).' · Zona: '.e($v['distrito']).'</p>'
           .'<p>Apoyo psicológico: '.($v['psico']?'sí':'no').' · Apoyo legal: '.($v['legal']?'sí':'no').'</p><p>Revísalo en el panel admin.</p>');

        $_SESSION['codigo'] = $codigo;
        header('Location: gracias.php'); exit;
    }
}
include 'partials/header.php';
?>
<section class="wrap narrow">
  <h1>Hacer un reporte</h1>
  <p class="muted">Lo que cuentes es confidencial. Los campos de contacto son opcionales.</p>

  <?php if ($errores): ?>
    <div class="alert"><ul><?php foreach ($errores as $er) echo '<li>'.e($er).'</li>'; ?></ul></div>
  <?php endif; ?>

  <form method="post" class="form" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <label>Tipo de violencia *
      <select name="tipo" required>
        <option value="">— Selecciona —</option>
        <?php foreach ($tipos as $t) echo '<option'.($v['tipo']===$t?' selected':'').'>'.e($t).'</option>'; ?>
      </select>
    </label>

    <label>¿Qué ocurrió? *
      <textarea name="descripcion" id="descripcion" rows="6" maxlength="3000" required><?= e($v['descripcion']) ?></textarea>
      <small><span id="contador">0</span>/3000</small>
    </label>

    <div class="row">
      <label>Fecha aproximada
        <input type="date" name="fecha" max="<?= date('Y-m-d') ?>" value="<?= e($v['fecha']) ?>">
      </label>
      <label>Distrito o zona *
        <input type="text" name="distrito" maxlength="100" required value="<?= e($v['distrito']) ?>">
      </label>
    </div>

    <label class="check"><input type="checkbox" name="anonimo" id="anonimo" <?= $v['anonimo']?'checked':'' ?>> Quiero reportar de forma anónima (sin nombre)</label>

    <div id="contacto">
      <div id="bloque-nombre"><label>Nombre (opcional)<input type="text" name="nombre" maxlength="100" value="<?= e($v['nombre']) ?>"></label></div>
      <div class="row">
        <label>Correo (para recibir tu código y novedades)<input type="email" name="email" maxlength="150" value="<?= e($v['email']) ?>"></label>
        <label>Teléfono (opcional)<input type="tel" name="telefono" maxlength="20" value="<?= e($v['telefono']) ?>"></label>
      </div>
    </div>

    <fieldset>
      <legend>¿Qué apoyo necesitas?</legend>
      <label class="check"><input type="checkbox" name="psico" <?= $v['psico']?'checked':'' ?>> 🧠 Apoyo psicológico</label>
      <label class="check"><input type="checkbox" name="legal" <?= $v['legal']?'checked':'' ?>> ⚖️ Orientación legal</label>
    </fieldset>

    <label class="check"><input type="checkbox" name="consentimiento" required> Acepto que mis datos se traten de forma confidencial para gestionar mi caso *</label>

    <button class="btn" type="submit">Enviar reporte</button>
  </form>
</section>
<?php include 'partials/footer.php'; ?>
