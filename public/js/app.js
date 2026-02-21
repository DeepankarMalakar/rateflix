/**
 * MovieReview Application – app.js
 * Handles UI interactivity: tabs, star rating widget, poster preview.
 */

document.addEventListener('DOMContentLoaded', () => {

  // ── Tab Switcher ────────────────────────────────────────────────────────
  const tabBtns    = document.querySelectorAll('.tab-btn');
  const tabPanels  = document.querySelectorAll('.tab-content');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;

      tabBtns.forEach(b => b.classList.remove('active'));
      tabPanels.forEach(p => p.classList.remove('active'));

      btn.classList.add('active');
      document.getElementById(target)?.classList.add('active');
    });
  });

  // ── Interactive Star Rating Widget ─────────────────────────────────────
  const starWidget  = document.getElementById('starWidget');
  const ratingInput = document.getElementById('ratingInput');
  const ratingHint  = document.getElementById('ratingHint');

  if (starWidget && ratingInput) {
    const stars = starWidget.querySelectorAll('.star-btn');

    // Restore saved value (e.g. edit review page)
    const savedVal = parseInt(ratingInput.value, 10);
    if (savedVal > 0) {
      stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= savedVal));
    }

    stars.forEach(star => {
      // Hover: highlight up to hovered star
      star.addEventListener('mouseenter', () => {
        const val = parseInt(star.dataset.val);
        stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= val));
        if (ratingHint) ratingHint.textContent = `${val}/10`;
      });

      // Leave: revert to selected value
      starWidget.addEventListener('mouseleave', () => {
        const current = parseInt(ratingInput.value, 10) || 0;
        stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= current));
        if (ratingHint) ratingHint.textContent = current > 0 ? `${current}/10` : 'Click a star to rate';
      });

      // Click: lock value
      star.addEventListener('click', () => {
        const val = parseInt(star.dataset.val);
        ratingInput.value = val;
        stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= val));
        if (ratingHint) ratingHint.textContent = `${val}/10 selected`;
      });
    });
  }

  // ── Poster URL Preview ──────────────────────────────────────────────────
  const posterUrlInput = document.getElementById('poster_url');
  const posterPreview  = document.getElementById('posterPreview');
  const posterImg      = document.getElementById('posterImg');

  if (posterUrlInput && posterImg) {
    let debounceTimer;

    posterUrlInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        const url = posterUrlInput.value.trim();
        if (url) {
          posterImg.src = url;
          if (posterPreview) posterPreview.style.display = 'block';
          posterImg.onerror = () => {
            if (posterPreview) posterPreview.style.display = 'none';
          };
        } else {
          if (posterPreview) posterPreview.style.display = 'none';
        }
      }, 500);
    });
  }

  // ── Flash Message Auto-Dismiss ──────────────────────────────────────────
  const flashMessages = document.querySelectorAll('.flash');
  flashMessages.forEach(msg => {
    setTimeout(() => {
      msg.style.transition = 'opacity 0.5s ease';
      msg.style.opacity = '0';
      setTimeout(() => msg.remove(), 500);
    }, 5000);
  });

  // ── Confirm Delete Dialogs (data-confirm attribute) ─────────────────────
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
      if (!confirm(el.dataset.confirm)) {
        e.preventDefault();
      }
    });
  });

});
