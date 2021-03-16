<?php
  session_start();
?>

<?php if(!isset($_SESSION['username'])) : ?>
  <p>You're not logged in so you can't see the secret</p>
  <?php elseif(isset($_SESSION['username'])) : ?>
    <p>You made it <?= $_SESSION['username'] ?>!</p>
<?php endif; ?>