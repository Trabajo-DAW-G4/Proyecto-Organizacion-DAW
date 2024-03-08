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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                        <th class='px-6 py-4'>Detalles del Pedido</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($resultados as $resultado) {
            echo "<tr class='bg-gray-200'>
                    <td class='px-6 py-4'>{$resultado['user']}</td>
                    <td class='px-6 py-4'>{$resultado['order_details']}</td>
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
