<?php

session_start();

//if (!isset($_SESSION["user"])) header("location:../index.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../setup.css">
    <title>Configuración del Firewall</title>
</head>
<body>
    <header>
        <center>
        <p>Configuración del Firewall</p>
        </center>
    </header>
    <br><br>
    <div id="contenedor">
        <br>
        <?php
            require '../funciones.php';
            /* Añade redirección, por lo que podemos obtener stderr. */
            $gestor = popen('sudo iptables -nL | grep INPUT 2>&1', 'r');
            $leer = fread($gestor, 2096);
            //echo $leer;
            pclose($gestor);
            if (strpos($leer, "ACCEPT") !== false)
            {
                
                ElaborarResultadosFirewall("Entrada [INPUT] ACCEPT", "cambiarinputtodrop", "Cambiar a DROP");
            }
            else
            {
                ElaborarResultadosFirewall("Entrada [INPUT] DROP", "cambiarinputtoaccept", "Cambiar a ACCEPT");
            }
        ?>

    </div>
</body>
</html>



<?php

    if (isset($_POST["cambiarinputtodrop"]))
    {
        popen('sudo iptables -P INPUT DROP', 'r');
    }
    else if (isset($_POST["cambiarinputtoaccept"]))
    {
        popen('sudo iptables -P INPUT ACCEPT', 'r');
    }

?>