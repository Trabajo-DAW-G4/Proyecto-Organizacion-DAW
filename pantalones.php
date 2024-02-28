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
    // Modifica la consulta para filtrar por la categoría "Pantalon"
    $query = "SELECT * FROM productos_ropa WHERE categoria = 'Pantalon' ORDER BY nombre";
    $statement = $conn->prepare($query);
    $statement->execute();
    $productos_ropa = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Nuestros Productos</h2>

    <div class="container-menu">
        <?php foreach($productos_ropa as $producto): ?>
            <figure class="figure">
                <img src="<?= $producto['ruta_foto'] ?>" alt="<?= $producto['nombre'] ?>" width="200" height="200">
                <figcaption><?= $producto['nombre'] ?> - $<?= $producto['precio'] ?></figcaption>
                <form method="post" action="orders.php">
                    <input type="hidden" name="product_id" value="<?= $producto['id'] ?>">
                </form>
            </figure>
        <?php endforeach; ?>
    </div>

    <?php if(count($productos_ropa) > 0): ?>
        <form method="post" action="orders.php">
            <table border="1">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Talla</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                </tr>
                <?php foreach($productos_ropa as $producto): ?>
                    <tr>
                        <td><?= $producto['nombre'] ?></td>
                        <td><?= $producto['precio'] ?></td>
                        <td><?= $producto['talla'] ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $producto['id'] ?>]" value="0" min="0" max="<?= $producto['stock'] ?>">
                            <?php if ($producto['stock'] <= 0): ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5"><input type="submit" value="Realizar Pedido"></td>
                </tr>
            </table>
        </form>
    <?php else: ?>
        <p>No se encontraron resultados</p>
    <?php endif; ?>

    <form method="post" action="logout.php">
        <button type="submit">Cerrar Sesión</button>
    </form>

    <?php
    $conn = null;
} else {
    echo "Error al conectar a la Base de datos";
}
?>
