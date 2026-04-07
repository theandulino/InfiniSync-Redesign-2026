<?php
require '../../data/db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password FROM is_users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $update = $conn->prepare("UPDATE is_users SET last_login = NOW() WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();

            header("Location: /account");
            exit();

        } else {
            echo "Falsches Passwort!";
        }

    } else {
        echo "User nicht gefunden!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <?php include '../../data/php/meta.php';?>
    <title>InfiniSync - Nahtlose Datenverwaltung und Synchronisierung</title>   
</head>
<body>
<?php include '../../data/php/topnav.php';?>
<div class="main w-50">
    <div class="account">
        <div class="login">
            <h1>Login</h1>
            <form action="index.php" method="post">
                <label for="username">Benutzername oder E-Mail:</label>
                <input type="text" name="login" placeholder="example@example.com" required><br><br>
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" placeholder="**********" required><br><br>
                <input type="submit" value="Login">
            </form>
            <p>Noch kein Konto? <a href="/account/register">Hier registrieren</a></p>
        </div>
    </div>
</div>
</body>
</html>