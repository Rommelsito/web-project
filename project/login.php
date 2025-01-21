<?php
// Configuración de la base de datos
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "test";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario de manera segura
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// Preparar la consulta para prevenir inyecciones SQL
$sql = "SELECT * FROM login WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    // Verificar la contraseña (asegúrate de usar password_hash en el registro)
    if (password_verify($pass, $row['password'])) {
        echo "Bienvenido: " . htmlspecialchars($user);
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
