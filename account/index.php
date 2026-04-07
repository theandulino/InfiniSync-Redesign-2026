<?php
require_once __DIR__ . '/../data/db/config.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Fetch current user info
$stmt = $conn->prepare("SELECT username, picture FROM is_users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update session picture
$_SESSION['username'] = $user['username'];
$_SESSION['picture'] = $user['picture'] ?? '/data/upload/profile/picture/img/profile_default.jpg';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>InfiniSync - Benutzerkonto</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<?php include __DIR__ . '/../data/php/topnav.php'; ?>
<div class="main account dashboard">
    <div class="grid-item">
        <h2>Dashboard</h2>
        <p>Willkommen <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </div>
    <div class="grid-item">
    <div class="profile-upload">
        <img id="profileImg" src="<?php echo htmlspecialchars($_SESSION['picture']); ?>" alt="Profilbild">
        <div id="uploadContainer">
            <p>Click or drag new image here (512x512)</p>
            <input type="file" id="fileInput" accept="image/jpeg,image/png,image/gif,image/webp" hidden>
        </div>
        <div id="message"></div>
    </div>
</div>
<div class="grid-item">
    <a href="logout">Logout</a>
    </div>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const uploadContainer = document.getElementById('uploadContainer');
const messageDiv = document.getElementById('message');
const profileImg = document.getElementById('profileImg');

uploadContainer.addEventListener('click', () => fileInput.click());
uploadContainer.addEventListener('dragover', e => { e.preventDefault(); uploadContainer.style.backgroundColor = '#f0f0f0'; });
uploadContainer.addEventListener('dragleave', e => { uploadContainer.style.backgroundColor = ''; });
uploadContainer.addEventListener('drop', e => {
    e.preventDefault();
    uploadContainer.style.backgroundColor = '';
    fileInput.files = e.dataTransfer.files;
    uploadImage();
});
fileInput.addEventListener('change', uploadImage);

function uploadImage() {
    const file = fileInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => profileImg.src = e.target.result;
    reader.readAsDataURL(file);

    const formData = new FormData();
    formData.append('image', file);

    fetch('upload.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            messageDiv.className = 'message ' + (data.success ? 'success' : 'error');
            messageDiv.textContent = data.message;
            if (data.success) profileImg.src = data.filepath; // update session file
        })
        .catch(err => {
            messageDiv.className = 'message error';
            messageDiv.textContent = 'Upload error: ' + err.message;
        });
}
</script>
</body>
</html>