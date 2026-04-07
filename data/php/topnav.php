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
      <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
      <?php if ($userpicture): ?>
        <img src="<?php echo htmlspecialchars($userpicture, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" class="nav-avatar">
      <?php endif; ?>
      
    </a>
  <?php else: ?>
    <a href="/account" class="login_btn"> Login/Register</a>
  <?php endif; ?>
    <a href="javascript:void(0);" class="icon" onclick="responsivemenu()">
    <i class="fa fa-bars"></i>
  </a>
</div>
<!-- Cookie Consent by TermsFeed https://www.TermsFeed.com -->
<script type="text/javascript" src="https://www.termsfeed.com/public/cookie-consent/4.2.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
document.addEventListener('DOMContentLoaded', function () {
cookieconsent.run({"notice_banner_type":"simple","consent_type":"express","palette":"dark","language":"de","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false,"website_name":"InfiniSync"});
});
</script>

<noscript>Free cookie consent management tool by <a href="https://www.termsfeed.com/">TermsFeed</a></noscript>
<!-- End Cookie Consent by TermsFeed https://www.TermsFeed.com -->

