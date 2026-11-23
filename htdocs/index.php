<?php
require 'config.php';
$titulo = 'Inicio';
include 'partials/header.php';

// Detecta automáticamente la imagen de fondo en assets/img/
$fondoHero = '';
foreach (['jpg', 'jpeg', 'png', 'webp', 'JPG', 'JPEG', 'PNG', 'WEBP'] as $ext) {
    if (file_exists("assets/img/fondo-hero.$ext")) {
        $fondoHero = "assets/img/fondo-hero.$ext";
        break;
    }
}

// Aplica la imagen de fondo con un velo oscuro/morado para que el texto blanco se lea bien
$heroStyle = $fondoHero
    ? "background: linear-gradient(135deg, rgba(46, 16, 101, 0.62), rgba(88, 28, 135, 0.62)), url('" . e($fondoHero) . "') center/cover no-repeat;"
    : "";

// Carga automática de imágenes del carrusel desde assets/img/
$slides = [];
$baseSlides = [
    ['file' => 'assets/img/hero.png', 'alt' => 'Prevención de la violencia', 'caption' => 'Prevención de la violencia · Construyamos una sociedad más justa, igualitaria y segura'],
    ['file' => 'assets/img/logo.png', 'alt' => 'Acompañamiento y prevención', 'caption' => 'Acompañamiento confidencial · Orientación psicológica y legal gratuita'],
];
foreach ($baseSlides as $s) {
    if (file_exists($s['file'])) {
        $slides[] = $s;
    }
}

// Detecta automáticamente slide-1.png, slide-2.jpg, slide-3.png, etc.
$extraFiles = glob('assets/img/slide-*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}', GLOB_BRACE) ?: [];
sort($extraFiles, SORT_NATURAL | SORT_FLAG_CASE);
foreach ($extraFiles as $idx => $path) {
    $slides[] = [
        'file'    => $path,
        'alt'     => 'Campaña Voz Segura ' . ($idx + 1),
        'caption' => 'Voz Segura · Canal confidencial de apoyo y orientación (ODS 5)',
    ];
}

// Si aún no agregaste slide-1, slide-2..., muestra también ods5.png en el carrusel
if (empty($extraFiles) && file_exists('assets/img/ods5.png')) {
    $slides[] = [
        'file'    => 'assets/img/ods5.png',
        'alt'     => 'ODS 5 · Igualdad de género',
        'caption' => 'ODS 5 · Igualdad de género · Por una vida libre de violencia',
    ];
}
?>
<section class="hero"<?= $heroStyle ? ' style="' . $heroStyle . '"' : '' ?>>
  <div class="wrap">
    <span class="hero-badge">🔒 Canal seguro y confidencial · ODS 5</span>
    <h1>No estás sola. No estás solo.</h1>
    <p>Un espacio digital seguro para reportar situaciones de violencia y recibir orientación psicológica y legal gratuita.</p>
    <div class="actions">
      <a class="btn" href="reportar.php">Hacer un reporte</a>
      <a class="btn ghost" href="apoyo.php">Pedir apoyo</a>
    </div>

    <?php if (!empty($slides)): ?>
    <div class="carousel" id="heroCarousel" aria-label="Galería informativa">
      <div class="carousel-track">
        <?php foreach ($slides as $i => $slide): ?>
          <div class="carousel-slide">
            <img src="<?= e($slide['file']) ?>" alt="<?= e($slide['alt']) ?>" <?= $i === 0 ? '' : 'loading="lazy"' ?>>
            <div class="carousel-caption"><?= e($slide['caption']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if (count($slides) > 1): ?>
        <button type="button" class="carousel-btn prev" aria-label="Imagen anterior">&#10094;</button>
        <button type="button" class="carousel-btn next" aria-label="Imagen siguiente">&#10095;</button>
        <div class="carousel-dots"></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="wrap">
  <div class="emergency">
    <strong>⚠️ ¿Peligro inmediato?</strong> Llama a la Policía <a href="tel:105">105</a> o a la Línea 100 <a href="tel:100">100</a>. Esta plataforma no es un servicio de emergencia en tiempo real.
  </div>

  <div class="grid">
    <article class="card"><h3>🔒 Reporte anónimo</h3><p>Puedes reportar sin dar tu nombre. No guardamos tu IP ni datos de tu dispositivo.</p></article>
    <article class="card"><h3>🧠 Apoyo psicológico</h3><p>Solicita orientación emocional y te conectamos con profesionales y servicios.</p></article>
    <article class="card"><h3>⚖️ Orientación legal</h3><p>Te explicamos tus derechos y cómo denunciar, y te derivamos a servicios gratuitos.</p></article>
    <article class="card"><h3>📍 Seguimiento con código</h3><p>Recibes un código único para consultar el estado de tu caso sin crear cuenta.</p></article>
  </div>

  <h2>¿Cómo funciona?</h2>
  <ol class="steps">
    <li><strong>Cuéntanos qué pasó</strong> con el formulario seguro.</li>
    <li><strong>Recibes un código</strong> (y un correo de confirmación si lo deseas).</li>
    <li><strong>Nuestro equipo revisa</strong> y te deriva a apoyo psicológico o legal.</li>
    <li><strong>Consultas el estado</strong> cuando quieras con tu código.</li>
  </ol>

  <div class="ods">
    <img src="assets/img/ods5.png" alt="Objetivo de Desarrollo Sostenible 5: Igualdad de género" width="120" height="120">
    <p><strong>ODS 5 · Igualdad de género.</strong> Nuestra meta: contribuir a eliminar la violencia contra mujeres y niñas facilitando el acceso a reportes seguros, apoyo psicológico y orientación legal.</p>
  </div>

  <div class="note">
    <strong>Seguridad:</strong> usa el botón <em>Salida rápida</em> (arriba a la derecha) si necesitas salir de esta página de inmediato.
  </div>
</section>
<?php include 'partials/footer.php'; ?>
