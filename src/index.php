<?php
session_start();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = htmlspecialchars($_POST['address']);
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    
    if (empty($name) || !preg_match("/^[a-zA-Z]+$/", $name)) $errors['name'] = "Solo letras.";
    if (empty($surname) || !preg_match("/^[a-zA-Z]+$/", $surname)) $errors['surname'] = "Solo letras.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Formato de email inválido.";
    if (empty($password) || !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{5,}$/", $password)) $errors['password'] = "Contraseña inválida.";
    if ($password !== $confirm_password) $errors['confirm_password'] = "No coincide.";
    
    if (!empty($card_number)) {
        if (!preg_match("/^\d{16}$/", $card_number)) $errors['card_number'] = "Debe tener 16 dígitos.";
        $expiry_date_obj = DateTime::createFromFormat('Y-m', $expiry_date);
        if (!$expiry_date_obj || $expiry_date_obj <= new DateTime()) $errors['expiry_date'] = "Fecha no válida.";
        if (!preg_match("/^\d{3}$/", $cvv)) $errors['cvv'] = "CVV inválido.";
    }

    if (empty($errors)) {
        $_SESSION['form_data'] = $_POST;
        header('Location: process_registration.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: auto; padding: 10px; }
        label, input { display: block; margin-bottom: 10px; width: 100%; }
        input[type="submit"] { margin-top: 20px; }
        .error { color: red; font-size: 12px; }
    </style>
</head>
<body>
    <h2>Registro</h2>
    <form action="index.php" method="POST">
        <label>Nombre
            <input type="text" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            <span class="error"><?php echo $errors['name'] ?? ''; ?></span>
        </label>

        <label>Apellidos
            <input type="text" name="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
            <span class="error"><?php echo $errors['surname'] ?? ''; ?></span>
        </label>

        <label>Email
            <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
        </label>

        <label>Contraseña
            <input type="password" name="password" required>
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
        </label>

        <label>Confirmar Contraseña
            <input type="password" name="confirm_password" required>
            <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
        </label>

        <label>Dirección de Envío (Opcional)
            <input type="text" name="address" value="<?php echo htmlspecialchars($address ?? ''); ?>">
        </label>

        <label>Número de Tarjeta (Opcional)
            <input type="text" name="card_number" value="<?php echo htmlspecialchars($card_number ?? ''); ?>">
            <span class="error"><?php echo $errors['card_number'] ?? ''; ?></span>
        </label>

        <label>Fecha de Caducidad (YYYY-MM)
            <input type="month" name="expiry_date" value="<?php echo htmlspecialchars($expiry_date ?? ''); ?>">
            <span class="error"><?php echo $errors['expiry_date'] ?? ''; ?></span>
        </label>

        <label>Código de Seguridad (CVV)
            <input type="text" name="cvv" value="<?php echo htmlspecialchars($cvv ?? ''); ?>">
            <span class="error"><?php echo $errors['cvv'] ?? ''; ?></span>
        </label>

        <input type="submit" value="Registrarse">
    </form>
</body>
</html>
