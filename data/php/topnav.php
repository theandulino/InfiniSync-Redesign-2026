<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$userpicture = isset($_SESSION['picture']) ? $_SESSION['picture'] : (isset($_SESSION['user_picture']) ? $_SESSION['user_picture'] : null);
?>
<script>
function responsivemenu() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

<div class="topnav" id="myTopnav">
  <a href="/" class="active">Home</a>
  
  <?php if ($username): ?>
    <a href="/account" class="login_btn">
      <?php if ($userpicture): ?>
        <img src="<?php echo htmlspecialchars($userpicture, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" class="nav-avatar">
      <?php endif; ?>
      <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
    </a>
  <?php else: ?>
    <a href="/account" class="login_btn"> Login/Register</a>
  <?php endif; ?>
    <a href="javascript:void(0);" class="icon" onclick="responsivemenu()">
    <i class="fa fa-bars"></i>
  </a>
</div>