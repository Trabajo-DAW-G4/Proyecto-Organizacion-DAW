<?php
include("./connectDB.php");

// Intentar establecer la conexión
$conn = connectDB();

if ($conn) {
    $query = "SELECT usuarios.user, pedidos.order_details
              FROM usuarios
              JOIN pedidos ON usuarios.id = pedidos.customer_id";
    $statement = $conn->prepare($query);
    $statement->execute();
    $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) > 0) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Tus Pedidos</title>
            <!-- Agregar CDN de Tailwind CSS -->
            <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
            <link rel='shortcut icon' href='favicon.ico' type='image/x-icon'>
        </head>
        <body>
            <div class='container mx-auto'>
                <table class='table-auto border-collapse border border-gray-800'>
                    <thead>
                        <tr>
                            <th class='px-4 py-2 bg-gray-200 border border-gray-800'>Usuario</th>
                            <th class='px-4 py-2 bg-gray-200 border border-gray-800'>Detalles del Pedido</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($resultados as $resultado) {
            echo "<tr>
                    <td class='px-4 py-2 border border-gray-800'>{$resultado['user']}</td>
                    <td class='px-4 py-2 border border-gray-800'>{$resultado['order_details']}</td>
                  </tr>";
        }

        echo "</tbody></table>
            </div>
        </body>
        </html>";
    } else {
        echo "No hay datos en la tabla pedidos.";
    }
    $conn = null;
} else {
    echo "Error al conectar a la base de datos";
}
?>
