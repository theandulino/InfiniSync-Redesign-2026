<?php
require '../data/db/config.php';

// 🔒 1. Erst prüfen ob eingeloggt
if (!isset($_SESSION['user_id'])) {
    header("Location: /account/login");
    exit();
}

$userId = $_SESSION['user_id'];

// 🔍 2. User aus DB holen
$stmt = $conn->prepare("SELECT username, picture FROM is_users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ❗ 3. Prüfen ob User noch existiert
if (!$user) {
    session_destroy();
    header("Location: /account/login");
    exit();
}

// 🔄 4. Session aktualisieren (wichtig!)
$_SESSION['username'] = $user['username'];
$_SESSION['picture'] = $user['picture'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <?php include '../data/php/meta.php';?>
    <title>InfiniSync - Benutzerkonto</title>   
</head>
<body>

<?php include '../data/php/topnav.php';?>

<div class="main">
    <div class="account">
        <h1>Benutzerkonto</h1>

        <p>Willkommen <?php echo htmlspecialchars($_SESSION['username']); ?></p>

        <?php if (!empty($_SESSION['picture'])): ?>
            <img src="<?php echo htmlspecialchars($_SESSION['picture']); ?>" 
                 alt="Profilbild" 
                 style="width:100px; height:100px; border-radius:50%;">
        <?php endif; ?>

        <br><br>
        <a href="/account/logout">Logout</a>
    </div>
</div>

</body>
</html>