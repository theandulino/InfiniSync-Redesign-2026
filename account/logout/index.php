<?php
session_start();
session_destroy();

echo "Du wurdest ausgeloggt!";
header("Refresh: 2; url=/account/login");
exit;
?>