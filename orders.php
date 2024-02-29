<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<header>
        <nav class="bg-gray-900 py-4 flex items-center justify-between">

            <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">

            <ul class="flex m-5 gap-5">
                <li>
                    <a href="#" class="text-white hover:text-gray-300">Inicio</a>
                </li>
                <li>
                    <a href="#" class="text-white hover:text-gray-300">Sobre nosotros</a>
                </li>
                <li>
                    <a href="#" class="text-white hover:text-gray-300">Términos y privacidad</a>
                </li>
            </ul>
        </nav>
    </header>
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

            echo "<div class='container mx-auto p-8'>";
            echo "<p class='text-center text-xl font-bold mb-4'>Pedido realizado</p>";
            echo "<h2 class='text-center text-2xl font-bold mb-4'>Detalles del pedido:</h2>";
            echo "<table class='w-full border-collapse border border-gray-300'>";
            echo "<thead>";
            echo "<tr class='bg-gray-200'>";
            echo "<th class='px-6 py-4'>Nombre</th>";
            echo "<th class='px-6 py-4'>Cantidad</th>";
            echo "<th class='px-6 py-4'>Precio Ud</th>";
            echo "<th class='px-6 py-4'>Subtotal</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($orderDetails as $detail) {
                echo "<tr class='border-b border-gray-300'>";
                echo "<td class='text-center px-4 py-2'>" . htmlspecialchars($detail['name']) . "</td>";
                echo "<td class='text-center px-4 py-2'>" . $detail['quantity'] . " uds" . "</td>";
                echo "<td class='text-center px-4 py-2 text-blue-500'>" . $detail['unit_price'] . " €" . "</td>";
                echo "<td class='text-center px-4 py-2'>" . $detail['subtotal'] . " €" . "</td>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<td colspan='3' class='text-center py-4'><strong>Total pagado:</strong></td>";
            echo "<td class='text-center py-4 text-blue-500'><strong>$totalToPay €</strong></td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p class='text-center mt-8'>No se encontraron resultados</p>";
        }

        $conn = null;
    } else {
        echo "<p class='text-center'>Error al conectar a la base de datos</p>";
    }
}
?>
