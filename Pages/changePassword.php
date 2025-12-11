<?php
    require "../PHP/constants.php";

    $error = "";

    session_start();
    if (isset($_SESSION[$ERROR])){
        $error = $_SESSION[$ERROR];
        unset($_SESSION[$ERROR]);
    }

    if (!isset($_SESSION[$IS_LOGGED])){
        header("Location: ../admin.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $json = json_decode(file_get_contents("../$PASSWORD_FILE"),true);
        $savedPassword = $json[$PASSWORD];

        $inputOldPassword = $_POST["oldPassword"];
        $inputNewPassword = $_POST["newPassword"];

        if(password_verify($inputOldPassword, $savedPassword)){
            if (!password_verify($inputNewPassword, $savedPassword)){
                $json[$PASSWORD] = password_hash($inputNewPassword, PASSWORD_DEFAULT);
                
                $file = fopen("../$PASSWORD_FILE", "w");
                fwrite($file,json_encode($json, JSON_PRETTY_PRINT));

                fclose($file);
                header("Location: ../admin.php");
                exit;
            } else {
                $_SESSION[$ERROR] = "La nuova password deve essere diversa da quella vecchia";
            }
        } else {
            $_SESSION[$ERROR] = "Errore. Password vecchia errata";
        }

        header("Location: changePassword.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio password</title>
    <link rel="stylesheet" href="../CSS/loginStyle.css">
</head>
<body onload='passwordConfirmation("inputForm", "newPassword", "repeatPassword")'>
    <div id="loginContainer">
        <h1>CAMBIA LA PASSWORD DI ACCESSO</h1>
        <form action="" method="post" id="inputForm">
            <div>
                <label for="oldPasswordInput">Vecchia password</label><br>
                <input type="password" name="oldPassword" id="oldPasswordInput" required>
                <p onclick="togglePasswordVisibility('oldPasswordInput')">Mostra password</p>
                <label for="oldPasswordInput">Nuova password</label><br>
                <input type="password" name="newPassword" id="newPasswordInput" required>
                <p onclick="togglePasswordVisibility('newPasswordInput')">Mostra password</p>
                <label for="oldPasswordInput">Ripeti la nuova password</label><br>
                <input type="password" name="repeatPassword" id="repeatPasswordInput" required>
                <p onclick="togglePasswordVisibility('repeatPasswordInput')">Mostra password</p>
            </div>
            <br>
            <button id="loginBtn" type="submit">Cambia Password</button>
        </form>

        <p id="error"><?php echo $error;?></p>

        <a href="../admin.php"><button id="homeBtn">Torna alla pagina di configurazione</button></a> 
    </div>

    <script src="../JS/passwordManagement.js"></script>
</body>
</html>