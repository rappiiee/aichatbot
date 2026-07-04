// ==========================================================
// Main site JavaScript: nav toggle, smooth scroll, FAQ accordion
// ==========================================================

document.addEventListener('DOMContentLoaded', function () {

  // ---- Mobile nav toggle ----
  const navToggle = document.getElementById('navToggle');
  const mainNav = document.getElementById('mainNav');
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function () {
      mainNav.classList.toggle('open');
    });
  }

  // ---- Smooth scroll for in-page anchor links ----
  document.querySelectorAll('a[href^="index.php#"], a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href').split('#')[1];
      const target = document.getElementById(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        if (mainNav) mainNav.classList.remove('open');
      }
    });
  });

  // ---- FAQ Accordion ----
  document.querySelectorAll('.accordion-question').forEach(function (question) {
    question.addEventListener('click', function () {
      const item = this.closest('.accordion-item');
      const answer = item.querySelector('.accordion-answer');
      const isActive = item.classList.contains('active');

      // Close all other items
      document.querySelectorAll('.accordion-item').forEach(function (el) {
        el.classList.remove('active');
        el.querySelector('.accordion-answer').style.maxHeight = null;
      });

      // Toggle current item
      if (!isActive) {
        item.classList.add('active');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });

  // ---- Auto-dismiss flash alerts ----
  const alertBox = document.querySelector('.alert');
  if (alertBox) {
    setTimeout(function () {
      alertBox.style.transition = 'opacity 0.4s ease';
      alertBox.style.opacity = '0';
      setTimeout(function () { alertBox.remove(); }, 400);
    }, 4000);
  }

});
