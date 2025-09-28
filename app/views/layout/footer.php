<div class="muted" style="margin-top:16px">© <?= date('Y') ?> — <?= e(APP_NAME) ?></div>
</div>
<script>
  window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
      location.reload();
    }
  });
</script>

</body>
</html>
