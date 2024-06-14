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
    <title>Insert Data</title>
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
        margin-bottom: 30px;
        padding: 20px;
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
        margin-top : 33px;
        justify-content: space-between;
    }
    .btn-primary, .btn-success {
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
//ganti display error jadi 0 agar tidak muncul notifikasi, ubah jadi 1 agar muncul
ini_set('display_errors', '0');
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

require_once 'config_db.php';

$db = new ConfigDB();
$conn = $db->connect();

//      //error handling*
        // function checkNum($number) {
        //     if($number>1) {
        //       throw new Exception("Value must be 1 or below");
        //     }
        //     return true;
        //   }
        // function logError($error) {
        //     error_log($error, 3, 'error.log');
        //  }
        //  try {
        //     echo checkNum(2);	
        // } catch (Exception $e) {
        //     logError($e->getMessage());
        //     echo 'Error : '.$e->getMessage();
        // }
            
        // echo 'Finish';

        //untuk contoh
        //echo $nama;

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
        <div class="btn-container">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-success">Kembali</a>
        </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = Trim(Htmlentities($_POST['name']));
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
<footer>Project @copyright by Valentinus Digmadani Nugroho & M. Faren Rajendra Ratosila</footer>
</body>
</html>
