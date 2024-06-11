<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
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
        .login-container {
            background:#004040;
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
        <h1 class="text-center mb-4">Login</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter Password" required>
            </div>
            <div class="form-group">
                <p><a href="register.php">Belum punya akun? </a></p>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Login</button>
        </form>
        <?php
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);

            session_start();
            if (isset($_SESSION['login'])) {
                header('Location: index.php');
                exit();
            }

            require_once 'config_db.php';

            $db = new ConfigDB();
            $conn = $db->connect();

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
