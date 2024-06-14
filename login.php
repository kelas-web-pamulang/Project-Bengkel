<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-size:cover;
            background-color: #003333;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #999;
        }
        .form-control {
            padding-left: 40px;
            border-radius: 30px;
            box-shadow: none;
            border: 1px solid #ddd;
        }
        .btn-primary {
            background: #003333;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: bold;
        }
        .form-group a {
            color: black;
        }
        form {
            margin-top: -20px; 

        }
        .login-container {
            background:##FFFF;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;

        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-4">
            <img src="logo (1).png" alt="Company Logo" class="img-fluid" style="max-width: 200px;">
        </div>
        <form action="" method="post">
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <p><a href="register.php">Belum punya akun? </a></p>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
        </form>
        <?php
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

            session_start();
            if (isset($_SESSION['login'])) {
                header('Location: index.php');
                exit();
            }

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

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $query = $conn->prepare("SELECT id_user, email, full_name, password FROM users WHERE email = ?");
                $query->bind_param("s", $email);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $isPasswordMatch = password_verify($password, $user['password']);
                    if ($isPasswordMatch) {
                        $_SESSION['login'] = true;
                        $_SESSION['userId'] = $user['id_user'];
                        $_SESSION['userName'] = $user['full_name'];

                        setcookie('clientId', $user['id_user'], time() + 86400, '/');
                        setcookie('clientSecret', hash('sha256', $user['email']), time() + 86400, '/');
                        header('Location: index.php');
                    } else {
                        echo "<div class='alert alert-danger mt-3' role='alert'>User/Password is incorrect</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>User/Password is incorrect</div>";
                }
                $query->close();
            }
        ?>
    </div>
</body>
</html>
