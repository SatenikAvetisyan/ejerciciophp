<?php
session_start();

require_once 'connection.php';

if (!isset($_SESSION['form_data'])) {
    header('Location: register.php');
    exit();
}

$form_data = $_SESSION['form_data'];

$conn->select_db("mydatabase");

// Encriptar la contraseña
$hashed_password = password_hash($form_data['password'], PASSWORD_DEFAULT);

// Ajustar expiry_date para agregar un día y convertirlo a 'YYYY-MM-DD'
$form_data['expiry_date'] = $form_data['expiry_date'] . '-01';  // Agregar el día '01'

// Insertar los datos en la base de datos
$sql = "INSERT INTO users (name, surname, email, password, address, card_number, expiry_date, cvv) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssss",
    $form_data['name'],
    $form_data['surname'],
    $form_data['email'],
    $hashed_password,
    $form_data['address'],
    $form_data['card_number'],
    $form_data['expiry_date'],
    $form_data['cvv']
);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    echo "Registro completado con éxito.";
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

