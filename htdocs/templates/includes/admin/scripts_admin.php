<div id="toast" class="toast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script src="<?= JS ?>app.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var groups = Array.from(document.querySelectorAll('.nav-group'));

    groups.forEach(function (group) {
        var toggle = group.querySelector('.nav-toggle');
        if (!toggle) return;

        toggle.addEventListener('click', function () {
            var estaAbierto = group.classList.contains('open');

            groups.forEach(function (g) {
                g.classList.remove('open');
                var t = g.querySelector('.nav-toggle');
                if (t) t.setAttribute('aria-expanded', 'false');
            });

            if (!estaAbierto) {
                group.classList.add('open');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });
    });

    var menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
            e.stopPropagation(); // Evita que este mismo clic cierre el menú
            document.querySelector('.sidebar').classList.toggle('open');
        });
    }

    // Cierra el menú en celulares al tocar afuera
    document.addEventListener('click', function (e) {
        var sidebar = document.querySelector('.sidebar');
        if (!sidebar || !sidebar.classList.contains('open')) return;
        if (sidebar.contains(e.target) || e.target === menuToggle) return;
        sidebar.classList.remove('open');
    });

});
</script>

</body>
</html>