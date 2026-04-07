<?php
require_once __DIR__ . '/../data/db/config.php';

$uploadDir = __DIR__ . '/../data/upload/profile/picture/img/';
$maxFileSize = 5 * 1024 * 1024; // 5MB
$requiredWidth = 512;
$requiredHeight = 512;
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

$response = ['success' => false, 'message' => ''];

// Prüfen, ob User eingeloggt
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

// Prüfen, ob Datei hochgeladen wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    if ($file['size'] > $maxFileSize) {
        $response['message'] = 'File too large. Max 5MB.';
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $response['message'] = 'Invalid file type.';
    } else {
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            $response['message'] = 'File is not a valid image.';
        } elseif ($imageInfo[0] !== $requiredWidth || $imageInfo[1] !== $requiredHeight) {
            $response['message'] = "Image must be {$requiredWidth}x{$requiredHeight}.";
        } else {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('profile_', true) . '.' . $ext;
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $dbPath = '/data/upload/profile/picture/img/' . $filename;

                // Alte Datei löschen, wenn sie nicht das Standardbild ist
                if (isset($_SESSION['picture']) && $_SESSION['picture'] !== '/data/upload/profile/picture/img/profile_default.jpg') {
                    $oldFile = __DIR__ . '/..' . $_SESSION['picture'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Datenbank aktualisieren
                $stmt = $conn->prepare("UPDATE is_users SET picture = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param('si', $dbPath, $_SESSION['user_id']);
                $stmt->execute();
                $stmt->close();

                // Session aktualisieren
                $_SESSION['picture'] = $dbPath;

                $response['success'] = true;
                $response['message'] = 'Profile picture updated successfully.';
                $response['filepath'] = $dbPath;
            } else {
                $response['message'] = 'Failed to save file.';
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>