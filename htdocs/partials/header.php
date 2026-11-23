<?php
$titulo = $titulo ?? 'Inicio';
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?> · Voz Segura</title>
<link rel="icon" href="assets/img/favicon.png">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="top">
  <div class="wrap bar">
    <a class="logo" href="index.php">
      <img src="assets/img/favicon.png" alt="Voz Segura" width="32" height="32">
      <span>Voz Segura</span>
    </a>
    <button class="burger" id="burger" aria-label="Abrir menú">☰</button>
    <nav id="menu">
      <a href="index.php" class="<?= $pagina === 'index.php' ? 'active' : '' ?>">Inicio</a>
      <a href="reportar.php" class="<?= $pagina === 'reportar.php' ? 'active' : '' ?>">Reportar</a>
      <a href="apoyo.php" class="<?= $pagina === 'apoyo.php' ? 'active' : '' ?>">Apoyo</a>
      <a href="seguimiento.php" class="<?= $pagina === 'seguimiento.php' ? 'active' : '' ?>">Seguimiento</a>
    </nav>
    <a class="exit" href="https://www.google.com" onclick="location.replace('https://www.google.com');return false;">Salida rápida ✕</a>
  </div>
</header>
<main>
