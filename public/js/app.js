// Small front-end behaviours only (no backend): search overlay, password toggle, qty stepper.
document.addEventListener('DOMContentLoaded', function () {
    // Search overlay
    var overlay = document.getElementById('search-overlay');
    document.querySelectorAll('[data-search-open]').forEach(function (el) {
        el.addEventListener('click', function () { overlay && overlay.classList.add('is-open'); });
    });
    document.querySelectorAll('[data-search-close]').forEach(function (el) {
        el.addEventListener('click', function (e) { e.preventDefault(); overlay && overlay.classList.remove('is-open'); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay) overlay.classList.remove('is-open');
    });

    // Show / hide password
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    // Quantity stepper (wishlist)
    document.querySelectorAll('[data-qty]').forEach(function (wrap) {
        var out = wrap.querySelector('[data-qty-value]');
        wrap.querySelectorAll('[data-qty-step]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var next = Math.max(1, parseInt(out.textContent, 10) + parseInt(btn.dataset.qtyStep, 10));
                out.textContent = next;
            });
        });
    });

    // Filter pills / remove-from-list are visual only
    document.querySelectorAll('[data-pill]').forEach(function (pill) {
        pill.addEventListener('click', function () { pill.classList.toggle('is-selected'); });
    });
});
