<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insert Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        date_default_timezone_set('Asia/Jakarta');
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Insert Data Suku Cadang</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Nama Barang</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
                <label for="priceInput">Harga Barang</label>
                <input type="number" class="form-control" id="priceInput" name="price" placeholder="Enter Price" required>
            </div>
            <div class="form-group">
                <label for="categorySelect">Category</label>
                <?php
                    $categories = $conn->query("SELECT a.id, a.name FROM categories a");
                    echo "<select class='form-control form-select' id='categorySelect' name='category' required>";
                    echo "<option value=''>Pilih Category</option>";
                    while ($category = $categories->fetch_assoc()) {
                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="supplierSelect">Nama Supplier</label>
                <?php
                    $suppliers = $conn->query("SELECT b.id_supplier, b.nama_supplier FROM supplier b");
                    echo "<select class='form-control form-select' id='supplierSelect' name='supplier' required>";
                    echo "<option value=''>Pilih Supplier</option>";
                    while ($supplier = $suppliers->fetch_assoc()) {
                        echo "<option value='{$supplier['id_supplier']}'>{$supplier['nama_supplier']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="stockInput">Stock Barang</label>
                <input type="number" class="form-control" id="stockInput" name="stock" placeholder="Enter Stock" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-success">Kembali</a>
        </form>

        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $price = $_POST['price'];
                $category = $_POST['category'];
                $supplier = $_POST['supplier'];
                $stock = $_POST['stock'];
                $createAt = date('Y-m-d H:i:s');

                $query = "INSERT INTO products (name, price, id_category, id_supplier, stock, created_at) 
                         VALUES ('$name', '$price', '$category', '$supplier', '$stock', '$createAt')";

                if ($conn->query($query) === TRUE) {
                    echo "<div class='alert alert-success mt-3' role='alert'>Data inserted successfully</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . $query . "<br>" . $conn->error . "</div>";
                }
            }
            $conn->close();
        ?>
    </div>
    <footer class="text-center mt-5">Project @copyright by Valentinus Digmadani Nugroho & M. Faren Rajendra Ratosila</footer>
</body>
</html>
