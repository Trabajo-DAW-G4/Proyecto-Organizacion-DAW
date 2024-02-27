<?php
include("./connectDB.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantity'])) {
    session_start();
    $conn = connectDB();
    $customer_id = $_SESSION['id'];

    if ($conn) {
        $order_date = date("Y-m-d H:i:s");
        $totalToPay = 0;
        $orderDetails = [];
        foreach ($_POST['quantity'] as $productId => $quantity) {
            // Utiliza una única consulta para obtener la información del producto
            $query_price = "SELECT id, nombre, precio FROM productos_ropa WHERE id = :product_id";
            $statement_price = $conn->prepare($query_price);
            $statement_price->bindParam(':product_id', $productId);
            $statement_price->execute();
            $product = $statement_price->fetch(PDO::FETCH_ASSOC);

            if ($quantity > 0) {
                $unitPrice = $product['precio'];
                $subtotal = $unitPrice * intval($quantity);

                $orderDetail = [
                    "name" => $product['nombre'],
                    "quantity" => $quantity,
                    "unit_price" => $unitPrice,
                    "subtotal" => $subtotal
                ];
                $orderDetails[] = $orderDetail;
                $totalToPay += $subtotal;
            }
        }

        if (!empty($orderDetails)) {
            $orderDetailString = json_encode($orderDetails);

            echo "<p>Pedido realizado.</p>";
            echo "<h2>Detalles del pedido:</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio Ud</th>
                        <th>Subtotal</th>
                    </tr>";

            foreach ($orderDetails as $detail) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($detail['name']) . "</td>";
                echo "<td>" . $detail['quantity'] . " uds" . "</td>";
                echo "<td>" . $detail['unit_price'] . " €" . "</td>";
                echo "<td>" . $detail['subtotal'] . " €" . "</td>";
                echo "</tr>";
            }

            echo "<tr>
                    <td colspan='3'><strong>Total pagado:</strong></td>
                    <td><strong>$totalToPay €</strong></td>
                </tr>";
            echo "</table>";

            // Utiliza una única consulta para insertar el pedido
            $query_insert_order = "INSERT INTO pedidos (customer_id, order_date, order_details, total) VALUES (:customer_id, :order_date, :order_details, :total)";
            $statement_insert_order = $conn->prepare($query_insert_order);
            $statement_insert_order->bindParam(':customer_id', $customer_id);
            $statement_insert_order->bindParam(':order_date', $order_date);
            $statement_insert_order->bindParam(':order_details', $orderDetailString);
            $statement_insert_order->bindParam(':total', $totalToPay);
            $statement_insert_order->execute();
        } else {
            echo "<p>No ha seleccionado nada.</p>";
        }

        $conn = null;
    } else {
        echo "Error al conectar a la base de datos";
    }
}
?>
