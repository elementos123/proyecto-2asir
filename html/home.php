<?php

session_start();

if (!isset($_SESSION["user"])) header("location:index.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Panel de control</title>
</head>
<body>
    <header>
        <p>ControlADY</p>
    </header>
    <center>
    <br><br>
    <nav style="border-bottom: solid 3px #CCF381; height: 80px;">
        <ul>
            <li><a href="home.php">Inicio</a></li>
            <li><a href="servicios/firewall.php">Firewall</a></li>
            <li><a href="servicios/dns.php">DNS</a></li>
            <li><a href="servicios/dhcp.php">DHCP</a></li>
            <li><a href="servicios/estadoservicios.php">Estado servicios</a></li>
        </ul>
    </nav>
    
    <br><br><br><br>
    
        <h4>Bienvenido <?php echo $_SESSION["user"]; ?> desde aqui puedes acceder a las funciones que te proporcionamos</h4>
    <center>

</body>
</html>