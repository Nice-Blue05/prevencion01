<?php
require 'config.php';
$titulo = 'Reporte enviado';
$codigo = $_SESSION['codigo'] ?? null;
include 'partials/header.php';
?>
<section class="wrap narrow center">
  <?php if ($codigo): ?>
    <h1>✅ Reporte recibido</h1>
    <p>Guarda tu código de seguimiento:</p>
    <p class="codigo"><?= e($codigo) ?></p>
    <p class="muted">Con este código puedes consultar el estado de tu caso. No lo compartas con nadie.</p>
    <a class="btn" href="seguimiento.php">Consultar seguimiento</a>
    <a class="btn ghost" href="apoyo.php">Ver servicios de apoyo</a>
  <?php else: ?>
    <h1>No hay un reporte reciente</h1>
    <a class="btn" href="reportar.php">Hacer un reporte</a>
  <?php endif; ?>
</section>
<?php include 'partials/footer.php'; ?>
