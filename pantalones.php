<?php
include("./connectDB.php");

session_start();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (empty($user)) {
    header("Location: login.php");
    exit();
}

$conn = connectDB();

if ($conn) {
    $query = "SELECT * FROM productos_ropa WHERE categoria = 'Pantalon' ORDER BY nombre";
    $statement = $conn->prepare($query);
    $statement->execute();
    $productos_ropa = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <h2 class="text-3xl font-bold text-center m-8">Nuestros pantalones</h2>

    <div class="m-5 container-menu grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($productos_ropa as $producto) : ?>
            <figure class="figure">
                <img src="<?= $producto['ruta_foto'] ?>" alt="<?= $producto['nombre'] ?>" class="object-cover object-center">
                <figcaption class="text-center"><?= $producto['nombre'] ?> - $<?= $producto['precio'] ?></figcaption>
                <form method="post" action="orders.php">
                    <input type="hidden" name="product_id" value="<?= $producto['id'] ?>">
                </form>
            </figure>
        <?php endforeach; ?>
    </div>



    <?php if (count($productos_ropa) > 0) : ?>
        <form method="post" action="orders.php" class="mt-8">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Precio</th>
                        <th class="px-6 py-4">Talla</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_ropa as $producto) : ?>
                        <tr class="border-b border-gray-300">
                            <td class=" text-center px-4 py-2"><?= $producto['nombre'] ?></td>
                            <td class=" text-center px-4 py-2"><?= $producto['precio'] ?></td>
                            <td class=" text-center px-4 py-2"><?= $producto['talla'] ?></td>
                            <td class=" text-center px-4 py-2"><?= $producto['stock'] ?></td>
                            <td class=" text-center px-4 py-2">
                                <input type="number" name="quantity[<?= $producto['id'] ?>]" value="0" min="0" max="<?= $producto['stock'] ?>" class="w-2/3 border border-gray-300 rounded-md px-2 py-1">
                                <?php if ($producto['stock'] <= 0) : ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" class="text-center py-4"><input type="submit" value="Realizar Pedido" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    <?php else : ?>
        <p class="text-center mt-8">No se encontraron resultados</p>
    <?php endif; ?>

    <form method="post" action="logout.php" class="mt-8">
        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Cerrar Sesión</button>
    </form>


<?php
    $conn = null;
} else {
    echo "Error al conectar a la Base de datos";
}
?>