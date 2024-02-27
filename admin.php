<?php
include("./connectDB.php");

session_start();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$message = null;

function deleteProduct($conn, $id){
    $query = $conn->prepare("DELETE FROM productos_ropa WHERE id = :id");
    $query->bindParam(":id", $id);
    return $query->execute();
}

function editProduct($conn, $id){
    $query = $conn->prepare("SELECT * FROM productos_ropa WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function insertProduct($conn, $category, $name, $talla, $stock, $precio, $ruta_foto){
    if(isset($category, $name, $talla, $stock, $precio, $ruta_foto)){
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

function listProducts($conn){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['delete'])){
            $id = $_POST['delete_id'];
            deleteProduct($conn, $id);
            echo "<p>Producto eliminado correctamente.</p>";
        } else if(isset($_POST['edit'])){
            $id = $_POST['edit_id'];
            editProduct($conn, $id);
        }else if(isset($_POST['insert'])){
            $category = $_POST['category'];
            $name = $_POST['name'];
            $talla = $_POST['talla'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];

            // Verificar si se ha subido un archivo
            if(isset($_FILES['ruta_foto']) && $_FILES['ruta_foto']['error'] === UPLOAD_ERR_OK){
                $ruta_foto = 'assets/' . basename($_FILES['ruta_foto']['name']);
                // Mover el archivo al directorio de carga
                move_uploaded_file($_FILES['ruta_foto']['tmp_name'], $ruta_foto);
            } else {
                $ruta_foto = ''; // Definir una ruta predeterminada si no se proporciona una imagen
            }

            if(!empty($category) && !empty($name) && !empty($talla) && !empty($stock) && !empty($precio)){
                insertProduct($conn, $category, $name, $talla, $stock, $precio, $ruta_foto);
                $message = "Producto insertado correctamente.";
                echo $message;
            }else{
                $message = "Error al insertar el producto.";
                echo $message;
            }
        }
    }

    if(isset($_POST['update'])){
        $id = $_POST['product_id'];
        $category = $_POST['category'];
        $name = $_POST['name'];
        $talla = $_POST['talla'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];

        // Verificar si se ha subido un archivo
        if(isset($_FILES['ruta_foto']) && $_FILES['ruta_foto']['error'] === UPLOAD_ERR_OK){
            $ruta_foto = 'assets/' . basename($_FILES['ruta_foto']['name']);
            // Mover el archivo al directorio de carga
            move_uploaded_file($_FILES['ruta_foto']['tmp_name'], $ruta_foto);
        } else {
            // Si no se proporciona una nueva imagen, obtener la ruta existente del producto
            $productToEdit = editProduct($conn, $id);
            $ruta_foto = $productToEdit['ruta_foto'];
        }

        if(!empty($category) && !empty($name) && !empty($talla) && !empty($stock) && !empty($precio)){
            $query = $conn->prepare("UPDATE productos_ropa SET categoria = :category, nombre = :name, talla = :talla, stock = :stock, precio = :precio, ruta_foto = :ruta_foto WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->bindParam(":category", $category);
            $query->bindParam(":name", $name);
            $query->bindParam(":talla", $talla);
            $query->bindParam(":stock", $stock);
            $query->bindParam(":precio", $precio);
            $query->bindParam(":ruta_foto", $ruta_foto);

            if($query->execute()){
                $message = "Producto actualizado.";
                echo $message;
            } else {
                $message = "Error al actualizar.";
                echo $message;
            }
        } else {
            $message = "Campos incompletos al actualizar el producto.";
            echo $message;
        }
    }          

    $query = $conn->prepare("SELECT * FROM productos_ropa");
    $query->execute();

    $productToEdit = null;

    if(isset($_POST['edit_id'])){
        $id = $_POST['edit_id'];
        $productToEdit = editProduct($conn, $id);
    }
    ?>

    <form method="post" action="logout.php">
        <button type="submit">Cerrar Sesión</button>
    </form>

    <form method='post' enctype='multipart/form-data'>
        <label for='category'>Categoría:</label>
        <select name='category' id='category'>
            <option value='Camiseta' <?= ($productToEdit && $productToEdit["categoria"] == 'Camiseta') ? 'selected' : '' ?>>Camiseta</option>
            <option value='Sudadera' <?= ($productToEdit && $productToEdit["categoria"] == 'Sudadera') ? 'selected' : '' ?>>Sudadera</option>
            <option value='Pantalon' <?= ($productToEdit && $productToEdit["categoria"] == 'Pantalon') ? 'selected' : '' ?>>Pantalón</option>
        </select>

        <label for='name'>Nombre:</label>
        <input type='text' name='name' id='name' value='<?= ($productToEdit ? $productToEdit["nombre"] : "") ?>'>

        <label for='talla'>Talla:</label>
        <select name='talla' id='talla'>
            <option value='S' <?= ($productToEdit && $productToEdit["talla"] == 'S') ? 'selected' : '' ?>>S</option>
            <option value='M' <?= ($productToEdit && $productToEdit["talla"] == 'M') ? 'selected' : '' ?>>M</option>
            <option value='L' <?= ($productToEdit && $productToEdit["talla"] == 'L') ? 'selected' : '' ?>>L</option>
            <option value='XL' <?= ($productToEdit && $productToEdit["talla"] == 'XL') ? 'selected' : '' ?>>XL</option>
        </select>

        <label for='stock'>Stock:</label>
        <input type='text' name='stock' id='stock' value='<?= ($productToEdit ? $productToEdit["stock"] : "") ?>'>

        <label for='precio'>Precio:</label>
        <input type='text' name='precio' id='precio' value='<?= ($productToEdit ? $productToEdit["precio"] : "") ?>'>

        <label for='ruta_foto'>Ruta de la Foto:</label>
        <input type='file' name='ruta_foto' id='ruta_foto'>
        <?php if ($productToEdit && !empty($productToEdit['ruta_foto'])): ?>
            <img src="<?= $productToEdit['ruta_foto'] ?>" alt="<?= $productToEdit['nombre'] ?>" width='50' height='50'>
        <?php endif; ?>

        <input type='hidden' name='product_id' value='<?= ($productToEdit ? $productToEdit['id'] : "") ?>'>
        <button type='submit' name='<?= ($productToEdit ? "update" : "insert") ?>' id='insert-btn'><?= ($productToEdit ? "Editar" : "Insertar") ?> Producto</button>
    </form>

    <table border='1'>
        <tr>
            <th>Categoría</th>
            <th>Nombre</th>
            <th>Talla</th>
            <th>Stock</th>
            <th>Precio</th>
            <th>Foto</th>
            <th>Acciones</th>
        </tr>
    <?php
    foreach($query as $row){          
        echo "<tr>";
        echo "<td>" . $row["categoria"] . "</td>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["talla"] . "</td>";
        echo "<td>" . $row["stock"] . "</td>";
        echo "<td>" . $row["precio"] . "</td>";
        echo "<td><img src='" . $row["ruta_foto"] . "' alt='" . $row["nombre"] . "' width='50' height='50'></td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                    <input type='submit' name='delete' value='Eliminar'>
                </form>
                <form method='post'>
                    <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                    <input type='submit' name='edit' value='Editar' id='edit-btn'>
                </form>
              </td>";
        echo "</tr>";           
    }
    echo "</table>";    
}

function getBestSellingProducts($conn){
    $query = $conn->prepare("SELECT order_details FROM pedidos");
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $soldProducts = [];

    foreach($results as $result){
        $order_details = explode(",", $result['order_details']);

        foreach($order_details as $product_id){
            if(!isset($soldProducts[$product_id])){
                $soldProducts[$product_id] = 0;
            }
            $soldProducts[$product_id]++;          
        }
    }

    arsort($soldProducts);

    $productNames = [];
    foreach ($soldProducts as $product_id => $quantity) {
        $query = $conn->prepare("SELECT nombre FROM productos_ropa WHERE id = :id");
        $query->bindParam(":id", $product_id);
        $query->execute();
        $productName = $query->fetchColumn();

        if ($productName) {
            $productNames[$productName] = $quantity;
        }
    }
    return $productNames;
}

$conn = connectDB();
$bestSellingProducts = getBestSellingProducts($conn);

listProducts($conn);

echo "<h2>Productos Más Vendidos</h2>";
echo "<table border='1'>
        <tr>
            <th>Nombre</th>
            <th>Total Vendido</th>
        </tr>";

foreach ($bestSellingProducts as $productName => $quantity) {
    echo "<tr>";
    echo "<td>" . $productName . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
