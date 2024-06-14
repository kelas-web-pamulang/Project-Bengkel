<?php
session_start();

if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background:url('https://p4.wallpaperbetter.com/wallpaper/791/210/336/window-instrumento-workshop-wallpaper-preview.jpg')no-repeat center center fixed;
            background-size:cover;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            padding: 20px;
            background: #004040;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
            margin-bottom: 10px;
        }
        .btn-secondary {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-3 mb-4">Register Page</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Name</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <a href="login.php" class="btn btn-secondary mt-2">Login</a>
        <?php
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

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $createAt = date('Y-m-d H:i:s');

                $query = "INSERT INTO users (email, full_name, password, role, tgl_buat) VALUES (?, ?, ?, 'admin', ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $email, $name, $password, $createAt);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success' role='alert'>Data inserted successfully</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
                }

                $stmt->close();
                $conn->close();
            }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+MDJtIWYgvAbH9M56j8e0NeqN90B4" crossorigin="anonymous"></script>
</body>
</html>
