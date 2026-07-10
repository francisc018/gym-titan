/* ============================================================
   FORJA GYM — script global
   - Menú hamburguesa
   - Marca el enlace de navegación activo
   - Validación básica y envío AJAX (fetch) de formularios a PHP
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* Menú móvil */
  const burger = document.querySelector('.burger');
  const links  = document.querySelector('nav.links');
  if (burger && links) {
    burger.addEventListener('click', () => links.classList.toggle('open'));
  }

  /* Resalta el enlace de la página actual */
  const current = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('nav.links a, .pager .step').forEach(a => {
    const href = a.getAttribute('href');
    if (href === current) a.classList.add('active');
  });

  /* Revelado de elementos al hacer scroll */
  const revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
    revealEls.forEach((el, i) => {
      el.style.setProperty('--i', i % 6);
      io.observe(el);
    });
  }

  /* Contador animado para las estadísticas del hero (si existen) */
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    let current = 0;
    const step = Math.max(1, Math.round(target / 60));
    const timer = setInterval(() => {
      current += step;
      if (current >= target) { current = target; clearInterval(timer); }
      el.textContent = current;
    }, 20);
  });

  /* Envío genérico de formularios vía fetch hacia los scripts PHP */
  document.querySelectorAll('form[data-endpoint]').forEach(form => {
    const msg = form.querySelector('.form-msg');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Validación simple de campos requeridos
      let valid = true;
      form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.style.borderColor = '#ff5722';
        } else {
          field.style.borderColor = '';
        }
      });

      if (!valid) {
        showMsg(msg, 'Por favor completa todos los campos obligatorios.', true);
        return;
      }

      const endpoint = form.dataset.endpoint;
      const formData = new FormData(form);

      try {
        const res = await fetch(endpoint, { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ ok: res.ok, message: 'Respuesta recibida.' }));

        if (data.ok) {
          showMsg(msg, data.message || 'Datos guardados correctamente.', false);
          form.reset();
        } else {
          showMsg(msg, data.message || 'Ocurrió un error al guardar los datos.', true);
        }
      } catch (err) {
        // Si el archivo se abre sin servidor PHP, fetch fallará: mostramos aviso claro.
        showMsg(msg, 'No se pudo conectar con el servidor PHP. Verifica que el sitio corra sobre Apache/MySQL (XAMPP, etc.).', true);
      }
    });
  });

  function showMsg(el, text, isError) {
    if (!el) return;
    el.textContent = text;
    el.style.borderColor = isError ? '#ff5722' : '#4caf50';
    el.classList.add('show');
  }
});
