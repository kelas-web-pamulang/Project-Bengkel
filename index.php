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
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://www.cleverelements.com/wp-content/uploads/2024/01/warum-du-als-mechaniker-newsletter-versenden-solltest.png') no-repeat center center fixed;
            background-size: cover;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            font-family: 'Poppins', sans-serif;
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
        .table {
            background-color: #ecf0f1;
            color: #2c3e50;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #dcdde1;
        }
        .table thead {
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .table th {
            border: none;
        }
        .table td {
            border: none;
            padding: 10px;
        }
        .btn-custom {
            background-color: #e67e22;
            border-color: #e67e22;
        }
        .btn-custom:hover {
            background-color: #d35400;
            border-color: #d35400;
        }
        .form-control, .form-select {
            margin-right: 10px;
        }
        .btn-sm i {
            margin-right: 5px;
        }   
        .boxed-heading {
            background-color: rgba(236, 240, 241, 0.8); 
            color: #2c3e50; 
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #34495e; 
            text-align: center; 
            display: flex; 
            justify-content: center;
            align-items: center; 
            width: fit-content; 
            margin: auto; 
        }
        
    </style>
   
    <nav class="navbar navbar-expand-lg navbar-dark position-fixed w-100">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Project Bengkel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a id="logoutBtn" class="btn btn-danger" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5 ">
        <h1 class="text-center mb-4 boxed-heading mt-5"></i> List Suku Cadang</h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="" method="get" class="d-flex">
                    <input class="form-control me-2" placeholder="Cari Data" name="search"/>
                    <select name="search_by" class="form-select me-2">
                        <option value="">Cari Berdasarkan</option>
                        <option value="name">Nama Barang</option>
                        <option value="category">Category</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-2"></i> Cari</button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <a href="insert.php" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i> Tambah Data</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Id Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                    <th>Tgl. Insert Data</th>
                    <th colspan="2" class="text-center">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                <?php
                date_default_timezone_set('Asia/Jakarta');
                ini_set('display_errors', '1');
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
                        echo "<td><a class='btn btn-sm btn-primary' href='update.php?id=".$row['id']."'><i class='bi bi-pencil-square me-2'></i> Update</a></td>";
                        echo "<td><a class='btn btn-sm btn-danger' href='index.php?delete=".$row['id']."' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data?\");'><i class='bi bi-trash me-2'></i> Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No Data</td></tr>";
                }

                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer class="text-center mt-5">Project by Valentinus Digmadani Nugroho & M. Faren Rajendra Ratosila</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-U/FpXWsWM6lCRhLa0mS2nZ+5XxcSxXFImjNXIyEk1PQNLsAo69OB3O1G6YgVLk+B" crossorigin="anonymous"></script>
    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(event) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                event.preventDefault();
            } else {
                window.location.href = 'logout.php';
            }
        });
    </script>
</body>
</html>
