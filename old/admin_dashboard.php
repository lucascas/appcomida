<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}

// Guardar destinatarios de email en un archivo JSON
$emailsFile = 'emails.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emails = array_filter($_POST['emails']);
    file_put_contents($emailsFile, json_encode($emails));
}

$emails = file_exists($emailsFile) ? json_decode(file_get_contents($emailsFile)) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
</head>
<body>
    <h1>Configuración de Destinatarios del Menú</h1>
    <form method="POST" action="admin_dashboard.php">
        <?php for ($i = 0; $i < 5; $i++): ?>
            <div>
                <label>Email <?php echo $i + 1; ?>:</label>
                <input type="email" name="emails[]" value="<?php echo $emails[$i] ?? ''; ?>">
            </div>
        <?php endfor; ?>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
