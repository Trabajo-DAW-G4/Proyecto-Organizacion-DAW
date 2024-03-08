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
    $query = "SELECT * FROM productos_ropa ORDER BY nombre";
    $statement = $conn->prepare($query);
    $statement->execute();
    $productos_ropa = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
    <h2 class="text-3xl font-bold text-center my-8">Nuestros Productos</h2>

    <div class="container-menu grid grid-cols-1 md:grid-cols-3 gap-6 m-5">
        <?php foreach ($productos_ropa as $producto) : ?>
            <figure class="figure">
                <img src="<?= $producto['ruta_foto'] ?>" alt="<?= $producto['nombre'] ?>" class="w-full h-auto object-cover border-4 rounded-md border-black">
                <figcaption class="text-center"><?= $producto['nombre'] ?> - $<?= $producto['precio'] ?></figcaption>
                <form method="post" action="orders.php">
                    <input type="hidden" name="product_id" value="<?= $producto['id'] ?>">
                </form>
            </figure>
        <?php endforeach; ?>
    </div>

    <script>
        // Obtener todas las imágenes con la clase "object-cover"
        var images = document.querySelectorAll('.object-cover');

        // Agregar un evento de clic a cada imagen
        images.forEach(function(image) {
            // Cambiar el cursor al pasar sobre la imagen
            image.style.cursor = "pointer";

            image.addEventListener('click', function() {
                // Crear el modal
                var modal = document.createElement('div');
                modal.style.position = "fixed";
                modal.style.zIndex = "999";
                modal.style.left = "0";
                modal.style.top = "0";
                modal.style.width = "100%";
                modal.style.height = "100%";
                modal.style.overflow = "auto";
                modal.style.backgroundColor = "rgba(0, 0, 0, 0.9)";
                modal.style.display = "flex";
                modal.style.justifyContent = "center";
                modal.style.alignItems = "center";

                // Crear la imagen ampliada
                var modalImg = document.createElement('img');
                modalImg.src = this.src;
                modalImg.style.width = "80%";
                modalImg.style.maxWidth = "700px";
                modalImg.style.height = "auto";

                // Agregar la imagen ampliada al modal
                modal.appendChild(modalImg);

                // Agregar el modal al body
                document.body.appendChild(modal);

                // Ocultar el scroll del cuerpo
                document.body.style.overflow = 'hidden';

                // Cuando el usuario hace clic fuera de la imagen, cerrar el modal
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.style.display = "none";
                        // Restaurar el scroll del cuerpo
                        document.body.style.overflow = 'auto';
                    }
                });

                // Cuando el usuario hace clic en la "x", cerrar la imagen ampliada
                var close = document.createElement('span');
                close.innerHTML = "&times;";
                close.style.position = "absolute";
                close.style.top = "15px";
                close.style.right = "35px";
                close.style.color = "#f1f1f1";
                close.style.fontSize = "40px";
                close.style.fontWeight = "bold";
                close.style.transition = "0.3s";
                close.style.cursor = "pointer";

                close.addEventListener('click', function() {
                    modal.style.display = "none";
                    // Restaurar el scroll del cuerpo
                    document.body.style.overflow = 'auto';
                });

                // Agregar la "x" al modal
                modal.appendChild(close);
            });
        });
    </script>

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
                            <td class="text-center px-4 py-2"><?= $producto['nombre'] ?></td>
                            <td class="text-center px-4 py-2"><?= $producto['precio'] ?></td>
                            <td class="text-center px-4 py-2"><?= $producto['talla'] ?></td>
                            <td class="text-center px-4 py-2"><?= $producto['stock'] ?></td>
                            <td class="text-center px-4 py-2">
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
        <p class="text-center m-6 text-3xl">No se encontraron resultados</p>
    <?php endif; ?>

    <form method="post" action="logout.php">
        <button type="submit" class='bg-red-500 hover:bg-red-600 text-white font-bold p-3 text-center rounded w-full'>Cerrar Sesión</button>
    </form>

<?php
    $conn = null;
} else {
    echo "Error al conectar a la Base de datos";
}
?>