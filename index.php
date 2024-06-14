<?php
session_start();

if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['clientId']) && isset($_COOKIE['clientSecret'])) {
        
        if (validateUser($_COOKIE['clientId'], $_COOKIE['clientSecret'])) {
            $_SESSION['login'] = true;
        } else {
            header('Location: login.php');
            exit();
        }
    } else {
        header('Location: login.php');
        exit();
    }
}

//cookie logout
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('clientId', '', time() - 3600, '/');
    setcookie('clientSecret', '', time() - 3600, '/');
    header('Location: login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List Suku Cadang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background:url('https://th.bing.com/th/id/OIP.tljD9WAkwObrX_SlS12V0QHaEK?rs=1&pid=ImgDetMain') no-repeat center center fixed;
            background-size: cover;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
            padding-top: 50px;
        }
        .navbar {
            background-color: #34495e;
        }
        footer {
            background-color: #34495e;
            padding: 20px;
            width: 100%;
            color: #ecf0f1;
            margin-top: auto; 
        }
        .table {
            background-color: #ecf0f1;
            color: #2c3e50;
        }
        .btn-custom {
            background-color: #e67e22;
            padding: 9px;
            border-color: #e67e22;
        }
        .btn-custom:hover {
            background-color: #d35400;
            border-color: #d35400;
        }
        .form-control, .form-select {
            margin-right: 10px;
        }
        .btn-selected {
            background-color: #16a085 !important;
            border-color: #16a085 !important;
            color: white !important;
        }
    </style>
    <script>
        function confirmLogout(event) {
            event.preventDefault(); 
            const userConfirmed = confirm("Apakah Anda yakin ingin logout?");
            if (userConfirmed) {
                window.location.href = event.target.href; 
            }
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Project Bengkel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php" onclick="confirmLogout(event)">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <h1 class="text-center mt-5 mb-4">List Suku Cadang</h1>
    <div class="row mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <form action="" method="get" class="d-flex align-items-center">
                <input class="form-control" placeholder="Cari Data" name="search"/>
                <select name="search_by" class="form-select">
                    <option value="">Cari Berdasarkan</option>
                    <option value="name">Nama Barang</option>
                    <option value="category">Category</option>
                </select>
                <button type="submit" class="btn btn-success mx-2">Cari</button>
            </form>
            <a href="insert.php" class="btn btn-success btn-custom">Tambah Data <i class="fas fa-plus-circle"></i></a>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Id Barang</th>
            <th>Nama Barang</th>
            <th>Harga Barang</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Stock</th>
            <th>Tgl. Insert Data</th>
            <th colspan="2">Pilihan</th>
        </tr>
        </thead>
        <tbody>
        <?php
        date_default_timezone_set('Asia/Jakarta');
        //ganti display error jadi 0 agar tidak muncul notifikasi, ubah jadi 1 agar muncul
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

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

        //error handling*
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

        if (isset($_GET['delete'])) {
            $query = $db->update('products', [
                'deleted_at' => date('Y-m-d H:i:s')
            ], $_GET['delete']);
        }

        $searchCondition = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $searchBy = $_GET['search_by'];
            if ($searchBy == 'name') {
                $searchCondition = "AND a.name LIKE '%$search%'";
            } elseif ($searchBy == 'category') {
                $searchCondition = "AND b.name LIKE '%$search%'";
            }
        }

        $query = "SELECT a.id, a.name, a.price, a.stock, a.created_at, b.name as category_name, c.nama_supplier as supplier_name 
                  FROM products a 
                  LEFT JOIN categories b ON a.id_category = b.id 
                  LEFT JOIN supplier c ON a.id_supplier = c.id_supplier 
                  WHERE a.deleted_at IS NULL $searchCondition";

        $result = $conn->query($query);
        $totalRows = $result->num_rows;

        // echo $nama ; //cek error
        if ($totalRows > 0) {
            foreach ($result as $key => $row) {
                echo "<tr>";
                echo "<td>".($key + 1)."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['category_name']."</td>";
                echo "<td>".$row['supplier_name']."</td>";
                echo "<td>".$row['stock']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "<td><a class='btn btn-sm btn-info' href='update.php?id=".$row['id']."'><i class='fas fa-edit'></i> Update</a></td>";
                echo "<td><a class='btn btn-sm btn-danger' href='index.php?delete=".$row['id']."' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data?\")'><i class='fas fa-trash-alt'></i> Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' class='text-center'>No Data</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
</div>
<footer class="text-center">Project by Valentinus Digmadani Nugroho & M. Faren Rajendra Ratosila</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+MDJtIWYgvAbH9M56j8e0NeqN90B4" crossorigin="anonymous"></script>
</body>
</html>
