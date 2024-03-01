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
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Detalles del Pedido</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($resultados as $resultado) {
            echo "<tr>
                    <td>{$resultado['user']}</td>
                    <td>{$resultado['order_details']}</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No hay datos en la tabla pedidos.";
    }
    $conn = null;
} else {
    echo "Error al conectar a la base de datos";
}
?>
