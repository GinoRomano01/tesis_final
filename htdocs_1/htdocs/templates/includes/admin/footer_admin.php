<footer class="footer">
  <small>
    San Plácido - Córdoba, Barrio Zumarán | Sistema interno <?= date("Y") ?>
  </small>
</footer>
<script>
  window.SP_TRACKER = {
    endpoint:  '<?= URL ?>collect.php',
    usuarioId: <?= $_SESSION['usuario_id'] ?? 'null' ?>,
    clienteId: <?= $_SESSION['cliente_id']  ?? 'null' ?>,
    sesionId:  '<?= session_id() ?>',
  };
</script>
<script src="<?= URL ?>templates/assets/js/tracker.js"></script>
