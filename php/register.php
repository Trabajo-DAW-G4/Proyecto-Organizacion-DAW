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

// Procesamiento del formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Verifica si el usuario ya existe
    $stmt_check_user = $conn->prepare("SELECT * FROM usuarios WHERE username=?");
    $stmt_check_user->bind_param("s", $username);
    $stmt_check_user->execute();
    $result_user = $stmt_check_user->get_result();

    // Verifica si el correo electrónico ya está en uso
    $stmt_check_email = $conn->prepare("SELECT * FROM usuarios WHERE email=?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_email = $stmt_check_email->get_result();

    if ($result_user->num_rows > 0) {
        echo "El nombre de usuario ya está en uso. Por favor, elige otro.";
    } elseif ($result_email->num_rows > 0) {
        echo "El correo electrónico ya está registrado. Por favor, utiliza otro.";
    } else {
        // Inserta un nuevo usuario
        $stmt_insert_user = $conn->prepare("INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashea la contraseña
        $stmt_insert_user->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt_insert_user->execute()) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            echo "Error al registrar el usuario.";
        }

        $stmt_insert_user->close();
    }

    $stmt_check_user->close();
    $stmt_check_email->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        /* Estilos CSS aquí */
    </style>
</head>
<body>

    <h2>Registro de Usuario</h2>

    <form action="" method="post">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Registrarse</button>
    </form>

    <form action="login.php" method="get">
        <button type="submit">Ir a Iniciar Sesión</button>
    </form>

</body>
</html>