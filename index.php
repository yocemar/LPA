<?PHP

  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
?>

<?PHP build_navBlock(); ?>
<div id="content">
  This is the <b>
    <?PHP echo basename($_SERVER['PHP_SELF']); ?></b> page 1.
</div>
<script>
</script>
<?PHP
build_footer();
?>