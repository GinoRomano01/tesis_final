    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> San Plácido. Todos los derechos reservados.</p>
            <p class="mb-0">
                <a href="<?= URL ?>terminos" class="text-white text-decoration-none">Términos y Condiciones</a> | 
                <a href="<?= URL ?>privacidad" class="text-white text-decoration-none">Política de Privacidad</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.SP_TRACKER = {
            endpoint:  '<?= URL ?>collect.php',
            usuarioId: <?= $_SESSION['usuario_id'] ?? 'null' ?>,
            clienteId: <?= $_SESSION['cliente_id']  ?? 'null' ?>,
            sesionId:  '<?= session_id() ?>',
        };
    </script>
    <script src="<?= URL ?>templates/assets/js/tracker.js"></script>
    <?php include INCLUDES . 'chatbot.php'; ?>
    
</body>
</html>