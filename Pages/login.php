<?php
    require "../PHP/constants.php";

    $error = "";
    session_start();
    if (isset($_SESSION[$IS_LOGGED])){
        if ($_SESSION[$IS_LOGGED]){
            header("Location: ../admin.php");
            exit;
        } else {
            $error = "Password Errata! Riprovare";
            unset($_SESSION[$IS_LOGGED]);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $json = json_decode(file_get_contents("../$PASSWORD_FILE"),true);

        $inputPassword = $_POST[$PASSWORD];

        $savedPassword = $json[$PASSWORD];

        if (password_verify($inputPassword, $savedPassword)) {
            $_SESSION[$IS_LOGGED] = true;
        } else {
            $_SESSION[$IS_LOGGED] = false;
        }
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/loginStyle.css">
</head>
<body>
    <div id="loginContainer">
        <h1>È NECESSARIA L'AUTORIZZAZIONE</h1>
        <form action="" method="post">
            <div>
                <label for="passwordInput">Password</label><br>
                <input type="password" name="password" id="passwordInput" required>
                <p onclick="togglePasswordVisibility('passwordInput')">Mostra password</p>
            </div>
            <br>
            <button id="loginBtn" type="submit">Accedi</button>
        </form>

        <p id="error"><?php echo $error;?></p>

        <a href="../index.php"><button id="homeBtn">Torna alla Home</button></a> 
    </div>

    <script src="../JS/passwordManagement.js"></script>
</body>
</html>