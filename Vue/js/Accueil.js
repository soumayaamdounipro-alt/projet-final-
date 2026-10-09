/* ══════════════════════════════════════════
   COOK WITH SOUMI — script.js
   ══════════════════════════════════════════ */
 
/* ── NAVBAR : ombre au scroll ── */
const navbar = document.getElementById('navbar');
 
window.addEventListener('scroll', () => {
  if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 20);
});
 
/* ── REVEAL AU SCROLL (Intersection Observer) ── */
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.15 });
 
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
 
/* ── SMOOTH SCROLL pour les liens d'ancre ── */
document.querySelectorAll('a[href^="#"]').forEach(link => {
  link.addEventListener('click', (e) => {
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
 
/* ══════════════════════════════════════════
   DECO PILLS — Animations dynamiques
   ══════════════════════════════════════════ */
 
(function initDecoPills() {
  const pills = document.querySelectorAll('.deco-pill');
  if (!pills.length) return;
 
  /* ── 1. Entrée en cascade (fadeUp décalé) ── */
  pills.forEach((pill, i) => {
    pill.style.opacity    = '0';
    pill.style.transform  = 'translateY(24px) scale(0.92)';
    pill.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
 
    setTimeout(() => {
      pill.style.opacity   = '1';
      pill.style.transform = 'translateY(0) scale(1)';
    }, 600 + i * 220);
  });
 
  /* ── 2. Flottement indépendant (chaque pill a sa propre orbite) ── */
  const floatParams = [
    { ampY: 9,  ampX: 4,  period: 3800 },   // deco-1
    { ampY: 11, ampX: -5, period: 4400 },   // deco-2
    { ampY: 7,  ampX: 3,  period: 3200 },   // deco-3
  ];
 
  let startTime = null;
 
  function animatePills(ts) {
    if (!startTime) startTime = ts;
    const elapsed = ts - startTime;
 
    pills.forEach((pill, i) => {
      const p  = floatParams[i] || floatParams[0];
      const tY = Math.sin((elapsed / p.period) * 2 * Math.PI) * p.ampY;
      const tX = Math.cos((elapsed / p.period) * 2 * Math.PI) * p.ampX;
      pill.style.transform = `translate(${tX}px, ${tY}px) scale(1)`;
    });
 
    requestAnimationFrame(animatePills);
  }
 
  // Démarrer le flottement après l'entrée
  setTimeout(() => {
    startTime = null;
    requestAnimationFrame(animatePills);
  }, 600 + pills.length * 220 + 300);
 
  /* ── 3. Pulse au hover ── */
  pills.forEach(pill => {
    pill.addEventListener('mouseenter', () => {
      pill.style.transition = 'box-shadow 0.2s ease, background 0.2s ease';
      pill.style.boxShadow  = '0 8px 32px rgba(244,123,32,0.28)';
      pill.style.background = '#FFF3E8';
    });
    pill.addEventListener('mouseleave', () => {
      pill.style.boxShadow  = '';
      pill.style.background = '';
    });
  });
 
  /* ── 4. Particule confetti au clic ── */
  pills.forEach(pill => {
    pill.style.cursor = 'pointer';
    pill.addEventListener('click', (e) => {
      burstConfetti(e.clientX, e.clientY);
    });
  });
})();
 
/* ── Confetti burst helper ── */
function burstConfetti(x, y) {
  const colors  = ['#F47B20', '#FFD54F', '#3B1F0C', '#FF8A65', '#FFF3E8'];
  const count   = 18;
 
  for (let i = 0; i < count; i++) {
    const dot = document.createElement('span');
    dot.style.cssText = `
      position: fixed;
      left: ${x}px; top: ${y}px;
      width: ${4 + Math.random() * 6}px;
      height: ${4 + Math.random() * 6}px;
      border-radius: 50%;
      background: ${colors[Math.floor(Math.random() * colors.length)]};
      pointer-events: none;
      z-index: 9999;
      transform: translate(-50%, -50%);
      transition: none;
    `;
    document.body.appendChild(dot);
 
    const angle    = (Math.PI * 2 / count) * i + (Math.random() - 0.5) * 0.8;
    const distance = 40 + Math.random() * 60;
    const tx       = Math.cos(angle) * distance;
    const ty       = Math.sin(angle) * distance;
 
    requestAnimationFrame(() => {
      dot.style.transition  = `transform 0.7s cubic-bezier(0.25,0.46,0.45,0.94),
                               opacity 0.7s ease`;
      dot.style.transform   = `translate(calc(-50% + ${tx}px), calc(-50% + ${ty}px))`;
      dot.style.opacity     = '0';
    });
 
    setTimeout(() => dot.remove(), 800);
  }
}