<?php
// Conexión a la base de datos
$servername = "nombre_del_servidor";
$username = "root";
$password = "Tunivers";
$dbname = "tiendaropa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesamiento del formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Utiliza consultas preparadas para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);

    // Ejecuta la consulta
    $stmt->execute();

    // Obtiene el resultado
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Inicio de sesión exitoso
        session_start();
        $_SESSION['username'] = $username;
        header("Location: index2.html"); // Redirige a la página de bienvenida
        exit();
    } else {
        echo "Credenciales incorrectas";
    }

    $stmt->close();
}

$conn->close();
?>