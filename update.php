<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        date_default_timezone_set('Asia/Jakarta');
        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();
        $result = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $conn->begin_transaction();

            try {
                $name = $_POST['name'];
                $price = $_POST['price'];
                $category = $_POST['id']; 
                $supplier = $_POST['id_supplier'];
                $stock = $_POST['stock'];

                $query = $db->update('products', [
                    'name' => $name,
                    'price' => $price,
                    'id_category' => $category,
                    'id_supplier' => $supplier,
                    'stock' => $stock
                ], $_GET['id']);

                if ($query) {
                    $conn->commit();
                    echo "<div class='alert alert-success mt-3' role='alert'>Data updated successfully</div>";
                } else {
                    $conn->rollback();
                    echo "<div class='alert alert-danger mt-3' role='alert'>Error updating data</div>";
                }

                $result = $db->select("products", ['AND id=' => $_GET['id']]);
            } catch (Exception $e) {
                $conn->rollback();
                echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . $e->getMessage() . "</div>";
            }
        } else {
            $result = $db->select("products", ['AND id=' => $_GET['id']]);
        }

    ?>
    <div class="container">
        <h1 class="text-center mt-5">Ubah Data Suku Cadang</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Nama Barang</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter Name" required value="<?php echo isset($result[0]['name']) ? $result[0]['name'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="priceInput">Harga Barang</label>
                <input type="number" class="form-control" id="priceInput" name="price" placeholder="Enter Price" required value="<?php echo isset($result[0]['price']) ? $result[0]['price'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="categorySelect">Kategori</label>
                <select class="form-control" id="categorySelect" name="id" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                        $categories = $conn->query("SELECT id, name FROM categories");
                        while ($row = $categories->fetch_assoc()) {
                            $selected = ($row['id'] == $result[0]['id_category']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="supplierSelect">Nama Supplier</label>
                <select class="form-control" id="supplierSelect" name="id_supplier" required>
                    <option value="">Pilih Supplier</option>
                    <?php
                        $suppliers = $conn->query("SELECT id_supplier, nama_supplier FROM supplier");
                        while ($row = $suppliers->fetch_assoc()) {
                            $selected = ($row['id_supplier'] == $result[0]['id_supplier']) ? 'selected' : '';
                            echo "<option value='{$row['id_supplier']}' $selected>{$row['nama_supplier']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="stockInput">Stok</label>
                <input type="number" class="form-control" id="stockInput" name="stock" placeholder="Enter Stock" required value="<?php echo isset($result[0]['stock']) ? $result[0]['stock'] : '' ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-info">Kembali</a>
        </form>

        <?php
            $conn->close();
        ?>
    </div>
    <footer class="text-center mt-5">Project @copyright by Valentinus Digmadani Nugroho & M. Faren Rajendra Ratosila</footer>
</body>
</html>
