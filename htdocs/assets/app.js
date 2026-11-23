document.getElementById('burger')?.addEventListener('click', () => {
  document.getElementById('menu').classList.toggle('open');
});

const anonimo = document.getElementById('anonimo');
const bloqueNombre = document.getElementById('bloque-nombre');
function toggleNombre() { if (anonimo && bloqueNombre) bloqueNombre.style.display = anonimo.checked ? 'none' : 'block'; }
anonimo?.addEventListener('change', toggleNombre);
toggleNombre();

const desc = document.getElementById('descripcion');
const cont = document.getElementById('contador');
function contar() { if (desc && cont) cont.textContent = desc.value.length; }
desc?.addEventListener('input', contar);
contar();

// Carrusel principal
document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.getElementById('heroCarousel');
  if (!carousel) return;

  const track = carousel.querySelector('.carousel-track');
  const slides = Array.from(track.children);
  const prevBtn = carousel.querySelector('.prev');
  const nextBtn = carousel.querySelector('.next');
  const dotsWrap = carousel.querySelector('.carousel-dots');

  if (slides.length <= 1) return;

  let index = 0;
  let timer = null;
  let touchStartX = 0;

  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', `Diapositiva ${i + 1}`);
    dot.addEventListener('click', () => { goTo(i); startAuto(); });
    dotsWrap?.appendChild(dot);
  });

  const dots = dotsWrap ? Array.from(dotsWrap.children) : [];

  function goTo(newIndex) {
    index = (newIndex + slides.length) % slides.length;
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === index));
  }

  function startAuto() {
    stopAuto();
    timer = setInterval(() => goTo(index + 1), 4500);
  }

  function stopAuto() {
    if (timer) clearInterval(timer);
  }

  prevBtn?.addEventListener('click', () => { goTo(index - 1); startAuto(); });
  nextBtn?.addEventListener('click', () => { goTo(index + 1); startAuto(); });
  carousel.addEventListener('mouseenter', stopAuto);
  carousel.addEventListener('mouseleave', startAuto);

  // Soporte táctil en celulares
  carousel.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    stopAuto();
  }, { passive: true });

  carousel.addEventListener('touchend', (e) => {
    const diff = touchStartX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 40) {
      goTo(diff > 0 ? index + 1 : index - 1);
    }
    startAuto();
  }, { passive: true });

  startAuto();
});
