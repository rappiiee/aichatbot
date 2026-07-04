// ==========================================================
// Admin Panel JavaScript: modal open/close, live search filter,
// delete confirmation
// ==========================================================

document.addEventListener('DOMContentLoaded', function () {

  // ---- Modal open/close ----
  document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const modal = document.getElementById(this.getAttribute('data-open-modal'));
      if (modal) modal.classList.add('open');
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const modal = this.closest('.modal-overlay');
      if (modal) modal.classList.remove('open');
    });
  });

  // Close modal when clicking outside the box
  document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  // ---- Populate "Edit" modal with row data ----
  document.querySelectorAll('[data-edit]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const data = JSON.parse(this.getAttribute('data-edit'));
      Object.keys(data).forEach(function (key) {
        const field = document.getElementById('edit_' + key);
        if (field) field.value = data[key];
      });
    });
  });

  // ---- Live search filter for tables ----
  const searchInput = document.getElementById('tableSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const term = this.value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(function (row) {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
      });
    });
  }

  // ---- Delete confirmation ----
  document.querySelectorAll('.delete-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!confirm('Are you sure you want to delete this item? This cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

});
