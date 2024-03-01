<?php
include("./connectDB.php");

// Intentar establecer la conexión
$conn = connectDB();

if ($conn) {
    // Consulta SQL para seleccionar los datos de la tabla pedidos
    $query = "SELECT order_details FROM pedidos";
    $statement = $conn->prepare($query);
    $statement->execute();
    $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo "Conexión exitosa a la base de datos";

    if (count($resultados) > 0) {
        // Mostrar los resultados en forma de tabla
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Detalles del Pedido</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($resultados as $resultado) {
            echo "<tr>
                    <td>{$resultado['order_details']}</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No hay datos en la tabla pedidos.";
    }

    // Cierra la conexión al final del script
    $conn = null;
} else {
    echo "Error al conectar a la base de datos";
}
?>
