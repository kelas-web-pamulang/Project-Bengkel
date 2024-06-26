<?php
session_start();

if (!isset($_SESSION['login']) && !isset($_COOKIE['login'])) {
    header('Location: login.php');
    exit();
}
?>

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
<style>
    body {
        background: url('https://p4.wallpaperbetter.com/wallpaper/791/210/336/window-instrumento-workshop-wallpaper-preview.jpg') no-repeat center center fixed;
        background-size: cover;
        background-color: #2c3e50;
        color: #ecf0f1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .container {
        flex: 1;
        max-width: 670px;
        margin-top: 40px;
        margin-bottom: 40px;
        padding: 32px;
        background: #004040;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    footer {
        background-color: #34495e;
        padding: 10px;
        width: 100%;
        color: #ecf0f1;
        text-align: center;
        position: relative;
        bottom: 0;
        left: 0;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .btn-container {
        display: flex;
        margin-top : 34px;
        justify-content: space-between;
    }
    .btn-primary, .btn-info {
        width: 48%;
        padding: 13px 20px; 
        font-size: 18px;
    }
    .alert {
        margin-top: 20px;
    }
</style>
<body>
    <?php
        date_default_timezone_set('Asia/Jakarta');
        require_once 'config_db.php';
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        //untuk memasukkan kedlm sentry
        require 'vendor/autoload.php';
        \Sentry\init([
        'dsn' => 'https://0edc357806aff26431f2cc015401142c@o4507428341153792.ingest.us.sentry.io/4507428352032768',
        // Specify a fixed sample rate
        'traces_sample_rate' => 1.0,
        // Set a sampling rate for profiling - this is relative to traces_sample_rate
        'profiles_sample_rate' => 1.0,
        ]);

        $db = new ConfigDB();
        $conn = $db->connect();
        $result = [];

        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn->begin_transaction();

            try {
                $name = Trim(Htmlentities($_POST['name']));
                $price = $_POST['price'];
                $category = $_POST['id'];
                $supplier = $_POST['id_supplier'];
                $stock = $_POST['stock'];
                $used_stock = $_POST['used_stock'];
                $add_stock = $_POST['add_stock'];

                // Ambil stok
                $query = "SELECT stock FROM products WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $stmt->bind_result($current_stock);
                $stmt->fetch();
                $stmt->close();

                // Periksa stok terpakai
                if ($used_stock > $current_stock) {
                    throw new Exception("Stok terjual lebih besar dari stok yang tersedia.");
                }

                // Hitung stok baru
                $new_stock = $current_stock;
                if ($used_stock > 0) {
                    $new_stock -= $used_stock;
                }
                if ($add_stock > 0) {
                    $new_stock += $add_stock;
                }

                $query = "UPDATE products SET name = ?, price = ?, id_category = ?, id_supplier = ?, stock = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sdiiii", $name, $price, $category, $supplier, $new_stock, $_GET['id']);

                if ($stmt->execute()) {
                    $conn->commit();
                    echo "<div class='alert alert-success mt-3' role='alert'>Data updated successfully. Stock adjusted.</div>";
                } else {
                    $conn->rollback();
                    echo "<div class='alert alert-danger mt-3' role='alert'>Error updating data</div>";
                }
                $stmt->close();

                $result = $db->select("products", ['AND id=' => $_GET['id']]);
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = $e->getMessage();
            }
        } else {
            $result = $db->select("products", ['AND id=' => $_GET['id']]);
        }
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Ubah Data Suku Cadang</h1>
        <?php
        if ($error_message) {
            echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . htmlentities($error_message) . "</div>";
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Nama Barang</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter Name" required value="<?php echo isset($_POST['name']) ? htmlentities($_POST['name']) : (isset($result[0]['name']) ? htmlentities($result[0]['name']) : '') ?>">
            </div>
            <div class="form-group">
                <label for="priceInput">Harga Barang</label>
                <input type="number" class="form-control" id="priceInput" name="price" placeholder="Enter Price" required value="<?php echo isset($_POST['price']) ? htmlentities($_POST['price']) : (isset($result[0]['price']) ? htmlentities($result[0]['price']) : '') ?>">
            </div>
            <div class="form-group">
                <label for="categorySelect">Kategori</label>
                <select class="form-control" id="categorySelect" name="id" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                        $categories = $conn->query("SELECT id, name FROM categories");
                        while ($row = $categories->fetch_assoc()) {
                            $selected = (isset($_POST['id']) && $_POST['id'] == $row['id']) ? 'selected' : ((isset($result[0]['id_category']) && $result[0]['id_category'] == $row['id']) ? 'selected' : '');
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
                            $selected = (isset($_POST['id_supplier']) && $_POST['id_supplier'] == $row['id_supplier']) ? 'selected' : ((isset($result[0]['id_supplier']) && $result[0]['id_supplier'] == $row['id_supplier']) ? 'selected' : '');
                            echo "<option value='{$row['id_supplier']}' $selected>{$row['nama_supplier']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="stockInput">Stok</label>
                <input type="number" class="form-control" id="stockInput" name="stock" placeholder="Enter Stock" required readonly value="<?php echo isset($_POST['stock']) ? htmlentities($_POST['stock']) : (isset($result[0]['stock']) ? htmlentities($result[0]['stock']) : '') ?>">
            </div>
            <div class="form-group">
                <label for="usedStockInput">Stok Terpakai</label>
                <input type="number" class="form-control" id="usedStockInput" name="used_stock" placeholder="Enter Used Stock" value="<?php echo isset($_POST['used_stock']) ? htmlentities($_POST['used_stock']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="addStockInput">Tambah Stok</label>
                <input type="number" class="form-control" id="addStockInput" name="add_stock" placeholder="Enter Jumlah Stock" value="<?php echo isset($_POST['add_stock']) ? htmlentities($_POST['add_stock']) : '' ?>">
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
