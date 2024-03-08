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
    ?>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav class="bg-gray-900 py-4 flex items-center justify-between">
                <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">
                <ul class="flex m-5 gap-5">
                    <li>
                        <a href="inicio.php" class="text-white hover:text-gray-300">Inicio</a>
                    </li>
                    <li>
                        <a href="templates/sobreNosotros.html" class="text-white hover:text-gray-300">Sobre nosotros</a>
                    </li>
                    <li>
                        <a href="templates/terminos.html" class="text-white hover:text-gray-300">Términos y privacidad</a>
                    </li>
                </ul>
            </nav>
        </header>
        <?php
        if (count($resultados) > 0) {
            echo "<table class='mt-5 text-center w-full border-collapse border border-gray-300'>
                    <thead>
                        <tr class='bg-gray-200'>
                            <th class='px-6 py-4'>Usuario</th>
                            <th class='px-6 py-4'>Nombre del Producto</th>
                            <th class='px-6 py-4'>Cantidad</th>
                            <th class='px-6 py-4'>Precio Unitario</th>
                            <th class='px-6 py-4'>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($resultados as $resultado) {
                // Decodificar la cadena JSON de detalles del pedido
                $detalles_pedido = json_decode($resultado['order_details'], true);
                
                // Iterar sobre cada elemento del pedido y mostrarlo en una fila de la tabla
                foreach ($detalles_pedido as $detalle) {
                    echo "<tr class='bg-gray-200'>
                            <td class='px-6 py-4'>{$resultado['user']}</td>
                            <td class='px-6 py-4'>{$detalle['name']}</td>
                            <td class='px-6 py-4'>{$detalle['quantity']}</td>
                            <td class='px-6 py-4'>{$detalle['unit_price']}</td>
                            <td class='px-6 py-4'>{$detalle['subtotal']}</td>
                          </tr>";
                }
            }

            echo "</tbody></table>";
        } else {
            echo "No hay datos en la tablapedidos.";
        }
        $conn = null;
        } else {
        echo "Error al conectar a la base de datos";
        }
        ?>
        </body>
        </html>