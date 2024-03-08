<?php
include("./connectDB.php");

session_start();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$message = null;

function deleteProduct($conn, $id)
{
    $query = $conn->prepare("DELETE FROM productos_ropa WHERE id = :id");
    $query->bindParam(":id", $id);
    return $query->execute();
}

function editProduct($conn, $id)
{
    $query = $conn->prepare("SELECT * FROM productos_ropa WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function insertProduct($conn, $category, $name, $talla, $stock, $precio, $ruta_foto)
{
    if (isset($category, $name, $talla, $stock, $precio, $ruta_foto)) {
        $query = $conn->prepare("INSERT INTO productos_ropa (categoria, nombre, talla, stock, precio, ruta_foto) VALUES (:category, :name, :talla, :stock, :precio, :ruta_foto)");
        $query->bindParam(":category", $category);
        $query->bindParam(":name", $name);
        $query->bindParam(":talla", $talla);
        $query->bindParam(":stock", $stock);
        $query->bindParam(":precio", $precio);
        $query->bindParam(":ruta_foto", $ruta_foto);
        return $query->execute();
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<script src="./scripts/darkmode.js"></script>
<header>
    <nav class="bg-gray-900 py-4 flex items-center justify-between">

        <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">

        <ul class="flex m-5 gap-5">
            <li>
                <a href="inicio.php" class="text-white hover:text-gray-300">Inicio</a>
            </li>
            <li>
            <button id="darkModeButton" class="flex items-center justify-center text-white rounded-md hover:text-gray-300">Dark Mode</button>
            </li>
            <li>
                <a href="templates/sobreNosotros.html" class="text-white hover:text-gray-300">Sobre nosotros</a>
            </li>
            <li>
                <a href="templates/terminos.html" class="text-white hover:text-gray-300">Términos y privacidad</a>
            </li>
        </ul>
    </nav>
</header> <?php
            function listProducts($conn)
            {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['delete'])) {
                        $id = $_POST['delete_id'];
                        deleteProduct($conn, $id);
                        echo "<p class='bg-red-500 hover:bg-red-600 text-white text-center font-bold py-2 px-4 rounded'>Producto eliminado correctamente.</p>";
                    } else if (isset($_POST['edit'])) {
                        $id = $_POST['edit_id'];
                        editProduct($conn, $id);
                    } else if (isset($_POST['insert'])) {
                        $category = $_POST['category'];
                        $name = $_POST['name'];
                        $talla = $_POST['talla'];
                        $stock = $_POST['stock'];
                        $precio = $_POST['precio'];

                        // Verificar si se ha subido un archivo
                        if (isset($_FILES['ruta_foto']) && $_FILES['ruta_foto']['error'] === UPLOAD_ERR_OK) {
                            $ruta_foto = 'assets/' . basename($_FILES['ruta_foto']['name']);
                            // Mover el archivo al directorio de carga
                            move_uploaded_file($_FILES['ruta_foto']['tmp_name'], $ruta_foto);
                        } else {
                            $ruta_foto = ''; // Definir una ruta predeterminada si no se proporciona una imagen
                        }

                        if (!empty($category) && !empty($name) && !empty($talla) && !empty($stock) && !empty($precio)) {
                            insertProduct($conn, $category, $name, $talla, $stock, $precio, $ruta_foto);
                            $message = "<p class='bg-red-500 hover:bg-red-600 text-white text-center font-bold py-2 px-4 rounded'>Producto insertado correctamente.</p>";
                            echo $message;
                        } else {
                            $message = "<p class='bg-red-500 hover:bg-red-600 text-white text-center font-bold py-2 px-4 rounded'>Error al insertar el producto.</p>";
                            echo $message;
                        }
                    }
                }

                if (isset($_POST['update'])) {
                    $id = $_POST['product_id'];
                    $category = $_POST['category'];
                    $name = $_POST['name'];
                    $talla = $_POST['talla'];
                    $stock = $_POST['stock'];
                    $precio = $_POST['precio'];

                    // Verificar si se ha subido un archivo
                    if (isset($_FILES['ruta_foto']) && $_FILES['ruta_foto']['error'] === UPLOAD_ERR_OK) {
                        $ruta_foto = 'assets/' . basename($_FILES['ruta_foto']['name']);
                        // Mover el archivo al directorio de carga
                        move_uploaded_file($_FILES['ruta_foto']['tmp_name'], $ruta_foto);
                    } else {
                        // Si no se proporciona una nueva imagen, obtener la ruta existente del producto
                        $productToEdit = editProduct($conn, $id);
                        $ruta_foto = $productToEdit['ruta_foto'];
                    }

                    if (!empty($category) && !empty($name) && !empty($talla) && !empty($stock) && !empty($precio)) {
                        $query = $conn->prepare("UPDATE productos_ropa SET categoria = :category, nombre = :name, talla = :talla, stock = :stock, precio = :precio, ruta_foto = :ruta_foto WHERE id = :id");
                        $query->bindParam(":id", $id);
                        $query->bindParam(":category", $category);
                        $query->bindParam(":name", $name);
                        $query->bindParam(":talla", $talla);
                        $query->bindParam(":stock", $stock);
                        $query->bindParam(":precio", $precio);
                        $query->bindParam(":ruta_foto", $ruta_foto);

                        if ($query->execute()) {
                            $message = "<p class='bg-red-500 hover:bg-red-600 text-white text-center font-bold py-2 px-4 rounded'>Producto actualizado.</p>";
                            echo $message;
                        } else {
                            $message = "<p class='bg-red-500 hover:bg-red-600 text-white text-center font-bold py-2 px-4 rounded'>Error al actualizar.</p>";
                            echo $message;
                        }
                    } else {
                        $message = "<p class='bg-red-500 hover:bg-red-600  text-white text-center font-bold py-2 px-4 rounded'>Campos incompletos al actualizar el producto.</p>";
                        echo $message;
                    }
                }

                $query = $conn->prepare("SELECT * FROM productos_ropa");
                $query->execute();

                $productToEdit = null;

                if (isset($_POST['edit_id'])) {
                    $id = $_POST['edit_id'];
                    $productToEdit = editProduct($conn, $id);
                }
            ?>
    <div class="flex flex-col items-center justify-center m-4">
        <form method="post" class="w-1/3" action="logout.php">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mr-2 w-full">
                Cerrar Sesión
            </button>
        </form>
        
        <button class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-1/3' type="submit"><a href="pedidos.php">Ver pedidos</a></button>
    </div>




    <form method='post' class="flex flex-col items-center justify-center gap-6 bg-red-200 p-10 max-w-lg mx-auto rounded" enctype='multipart/form-data'>

        <label for='category' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Categoría:</label>
        <select name='category' id='category' class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">
            <option value='Camiseta' <?= ($productToEdit && $productToEdit["categoria"] == 'Camiseta') ? 'selected' : '' ?>>Camiseta</option>
            <option value='Sudadera' <?= ($productToEdit && $productToEdit["categoria"] == 'Sudadera') ? 'selected' : '' ?>>Sudadera</option>
            <option value='Pantalon' <?= ($productToEdit && $productToEdit["categoria"] == 'Pantalon') ? 'selected' : '' ?>>Pantalón</option>
        </select>

        <label for='name' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Nombre:</label>
        <input type='text' name='name' id='name' value='<?= ($productToEdit ? $productToEdit["nombre"] : "") ?>' class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">

        <label for='talla' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Talla:</label>
        <select name='talla' id='talla' class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">
            <option value='S' <?= ($productToEdit && $productToEdit["talla"] == 'S') ? 'selected' : '' ?>>S</option>
            <option value='M' <?= ($productToEdit && $productToEdit["talla"] == 'M') ? 'selected' : '' ?>>M</option>
            <option value='L' <?= ($productToEdit && $productToEdit["talla"] == 'L') ? 'selected' : '' ?>>L</option>
            <option value='XL' <?= ($productToEdit && $productToEdit["talla"] == 'XL') ? 'selected' : '' ?>>XL</option>
        </select>

        <label for='stock' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Stock:</label>
        <input type='text' name='stock' id='stock' value='<?= ($productToEdit ? $productToEdit["stock"] : "") ?>' class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">

        <label for='precio' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Precio:</label>
        <input type='text' name='precio' id='precio' value='<?= ($productToEdit ? $productToEdit["precio"] : "") ?>' class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">

        <label for='ruta_foto' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Ruta de la Foto:</label>
        
        <input type='file' name='ruta_foto' id='ruta_foto' class="border border-gray-300 rounded-md justify-center w-full bg-white focus:outline-none focus:border-red-500 p-9">            
        

        <?php if ($productToEdit && !empty($productToEdit['ruta_foto'])) : ?>
            <img src="<?= $productToEdit['ruta_foto'] ?>" alt="<?= $productToEdit['nombre'] ?>" width='50' height='50'>
        <?php endif; ?>

        <input type='hidden' name='product_id' value='<?= ($productToEdit ? $productToEdit['id'] : "") ?>'>
        <button type='submit' name='<?= ($productToEdit ? "update" : "insert") ?>' id='insert-btn' class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"><?= ($productToEdit ? "Editar" : "Insertar") ?> Producto</button>
    </form>
    <table border="1" class="w-[] mx-auto border-collapse table-auto m-7">
        <thead class="bg-red-500 hover:bg-red-600 text-white">
            <tr>
                <th class="border p-4">Categoría</th>
                <th class="border p-4">Nombre</th>
                <th class="border p-4">Talla</th>
                <th class="border p-4">Stock</th>
                <th class="border p-4">Precio</th>
                <th class="border p-4">Foto</th>
                <th class="border p-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
                foreach ($query as $row) {
                    echo "<tr>";
                    echo "<td class='border p-2 text-center'>" . $row["categoria"] . "</td>";
                    echo "<td class='border p-2 text-center'>" . $row["nombre"] . "</td>";
                    echo "<td class='border p-2 text-center'>" . $row["talla"] . "</td>";
                    echo "<td class='border p-2 text-center'>" . $row["stock"] . "</td>";
                    echo "<td class='border p-2 text-center'>" . $row["precio"] . "</td>";
                    echo "<td class='border p-2 items-center'><img src='" . $row["ruta_foto"] . "' alt='" . $row["nombre"] . "' class='max-w-2 max-h-28'></td>";
                    echo "<td class='border p-2 text-center'>
                    <form method='post'>
                        <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                        <input type='submit' name='delete' value='Eliminar' class='bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded w-full'>
                    </form>
                    <form method='post'>
                        <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                        <input type='submit' name='edit' value='Editar' class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded w-full' id='edit-btn'>
                    </form>
                  </td>";
                    echo "</tr>";
                }
                echo "</table>";
            }



            $conn = connectDB();

            listProducts($conn);


        ?>