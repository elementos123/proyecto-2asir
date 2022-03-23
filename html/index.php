<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Setup ControlADY</title>
</head>
<body>
    <header>
        <p>ControlADY</p>
    </header>
    <br><br>
    <div id="contenedor">
        <center>
        <form action="" method="post">
            <br>
            <h2>Iniciar Sesión</h2>
            <hr style="border-color: #CCF381;">
            <br><br>
            <span id="user">
                Usuario: <input type="text" name="user" required>
            </span>
                
            <br><br>
            <span id="pass">
                Contraseña: <input type="password" name="pass" required>
            </span>

            <br><br>
            <input type="submit" name="enviar" value="Iniciar sesión">
            <br>
        </form>
        </center>
    </div>
</body>
</html>


<?php

if (isset($_POST["enviar"]))
{
    require 'funciones.php';
    ConsultarUsuarios($_POST["user"], $_POST["pass"]);
}

?>