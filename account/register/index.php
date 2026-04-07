<?php
require '../../data/db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Passwort hashen
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prüfen ob User existiert
    $stmt = $conn->prepare("SELECT id FROM is_users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "User existiert bereits!";
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO is_users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registrierung erfolgreich!";
        } else {
            echo "Fehler: " . $stmt->error;
        }
    }

    $stmt->close();
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
                <div class="register">
                    <h1>Registrieren</h1>
                    <form action="index.php" method="post">
                        <label for="username">Benutzername:</label>
                        <input type="text" id="username" name="username" required><br><br>
                        <label for="email">E-Mail:</label>
                        <input type="email" id="email" name="email" required><br><br>
                        <label for="password">Passwort:</label>
                        <input type="password" id="password" name="password" required><br><br>
                        <input type="submit" value="Registrieren">
                    </form>
                    <p>Bereits ein Konto? <a href="/account/login">Hier einloggen</a></p>
                </div>
            </div>
        </div>
    </body>
</html>