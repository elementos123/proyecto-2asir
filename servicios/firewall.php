<?php

session_start();

//if (!isset($_SESSION["user"])) header("location:../index.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="css/firewall.css">
    <title>Configuración del Firewall</title>
</head>
<body>
    <center>
        <header>
        
            <p>Configuración del Firewall</p>
            
        </header>
        <br><br>
        <nav style="border-bottom: solid 3px #CCF381; height: 80px;">
            <ul>
                <li><a href="../home.php">Inicio</a></li>
                <li style="cursor: pointer;" onclick="Mostrar_Ocultar('ocultarinput')">INPUT</li>
                <li style="cursor: pointer;" onclick="Mostrar_Ocultar('ocultarforward')">FORWARD</li>
                <li style="cursor: pointer;" onclick="Mostrar_Ocultar('ocultaroutput')">OUTPUT</li>
            </ul>
        </nav>
    

    <div id="contenedor">
        <br>
        <form action="firewall.php" method="get">
        <input type="submit" value="Guardar todas las reglas" name="guardarreglas" id="guardar">
        <?php
            require '../funciones.php';
            /* Añade redirección, por lo que podemos obtener stderr. */
            $gestor = popen('sudo iptables -nL | grep INPUT 2>&1', 'r');
            $leer = fread($gestor, 4096);
            //echo $leer;
            pclose($gestor);
            if (strpos($leer, "ACCEPT") !== false)
            {
                ElaborarResultadosFirewall("Entrada [INPUT] ACCEPT", "cambiarinputtodrop", "Cambiar a DROP", "input", 
                "guardarreglas", "cortartrinput", "eliminartrinput",
                "posicioninput", "origeninput", "destinoinput", "acceptodropinput", "eliminarbotonxinput", "ocultarinput");
            }
            else
            {
                ElaborarResultadosFirewall("Entrada [INPUT] DROP", "cambiarinputtoaccept", "Cambiar a ACCEPT", "input", 
                "guardarreglas", "cortartrinput", "eliminartrinput",
                "posicioninput", "origeninput", "destinoinput", "acceptodropinput", "eliminarbotonxinput", "ocultarinput");
            }


            /* Añade redirección, por lo que podemos obtener stderr. */
            $gestor = popen('sudo iptables -nL | grep FORWARD 2>&1', 'r');
            $leer = fread($gestor, 4096);
            //echo $leer;
            pclose($gestor);
            if (strpos($leer, "ACCEPT") !== false)
            {
                ElaborarResultadosFirewall("A través [FORWARD] ACCEPT", "cambiarforwardtodrop", "Cambiar a DROP", "forward", 
                "guardarreglas", "cortartrforward", "eliminartrforward",
                "posicionforward", "origenforward", "destinoforward", "acceptodropforward", "eliminarbotonxforward", "ocultarforward");
            }
            else
            {
                ElaborarResultadosFirewall("A través [FORWARD] DROP", "cambiarforwardtoaccept", "Cambiar a ACCEPT", "forward", 
                "guardarreglas", "cortartrforward", "eliminartrforward",
                "posicionforward", "origenforward", "destinoforward", "acceptodropforward", "eliminarbotonxforward", "ocultarforward");
            }



            /* Añade redirección, por lo que podemos obtener stderr. */
            $gestor = popen('sudo iptables -nL | grep OUTPUT 2>&1', 'r');
            $leer = fread($gestor, 4096);
            //echo $leer;
            pclose($gestor);
            if (strpos($leer, "ACCEPT") !== false)
            {
                ElaborarResultadosFirewall("Salida [OUTPUT] ACCEPT", "cambiaroutputtodrop", "Cambiar a DROP", "output", 
                "guardarreglas", "cortartroutput", "eliminartroutput",
                "posicionoutput", "origenoutput", "destinooutput", "acceptodropoutput", "eliminarbotonxoutput", "ocultaroutput");
            }
            else
            {
                ElaborarResultadosFirewall("Salida [OUTPUT] DROP", "cambiaroutputtoaccept", "Cambiar a ACCEPT", "output", 
                "guardarreglas", "cortartroutput", "eliminartroutput",
                "posicionoutput", "origenoutput", "destinooutput", "acceptodropoutput", "eliminarbotonxoutput", "ocultaroutput");
            }
        ?>
    </form>
    </div>
    </center>
</body>
</html>



<?php

    if (isset($_GET["cambiarinputtodrop"]))
    {
        $gestor = popen('sudo iptables -P INPUT DROP', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    else if (isset($_GET["cambiarinputtoaccept"]))
    {
        $gestor = popen('sudo iptables -P INPUT ACCEPT', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    else if (isset($_GET["cambiarforwardtodrop"]))
    {
        $gestor = popen('sudo iptables -P FORWARD DROP', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    else if (isset($_GET["cambiarforwardtoaccept"]))
    {
        $gestor = popen('sudo iptables -P FORWARD ACCEPT', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    else if (isset($_GET["cambiaroutputtodrop"]))
    {
        $gestor = popen('sudo iptables -P OUTPUT DROP', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    else if (isset($_GET["cambiaroutputtoaccept"]))
    {
        $gestor = popen('sudo iptables -P OUTPUT ACCEPT', 'r');
        echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
    }
    
    
    if (isset($_GET["guardarreglas"]))
    {
        GuardarReglas();
    }


    function GuardarReglas()
    {
        $gestor = popen('sudo echo "" > var/log/iptables.log', 'r');
        pclose($gestor);
        $gestor = popen('sudo iptables -F', 'r');
        $leer = fread($gestor, 4096);
        if (strpos($leer, "denied") !== false) 
        {
            echo "Se requieren permisos para ejecutar el comando";
        }
        else
        {
            for ($i=1; $i <= $_GET["trhayinput"]; $i++) 
            { 
                if (!empty($_GET["acceptodropinput".$i]))
                {   
                    
                    if (empty($_GET["origeninput".$i]) && empty($_GET["destinoinput".$i]))
                    {
                        $gestor = popen('sudo iptables -A INPUT -j '.$_GET["acceptodropinput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (empty($_GET["origeninput".$i]) && !empty($_GET["destinoinput".$i]))
                    {
                        $gestor = popen('sudo iptables -A INPUT -s '.$_GET["origeninput".$i].' -j '.$_GET["acceptodropinput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (!empty($_GET["origeninput".$i]) && empty($_GET["destinoinput".$i]))
                    {
                        $gestor = popen('sudo iptables -A INPUT -d '.$_GET["destinoinput".$i].' -j '.$_GET["acceptodropinput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else
                    {
                        $gestor = popen('sudo iptables -A INPUT -s '.$_GET["origeninput".$i].' -d '.$_GET["destinoinput".$i].' -j '.$_GET["acceptodropinput".$i].' ', 'r');
                        pclose($gestor);

                        $gestor = popen('sudo iptables -S ', 'r');
                        $leer = fread($gestor, 4096);
                        //echo $leer;
                        pclose($gestor);
                    }
                    
                }
            }
        }



            for ($i=1; $i <= $_GET["trhayforward"]; $i++) 
            { 
                if (!empty($_GET["acceptodropforward".$i]))
                {
                    if (empty($_GET["origenforward".$i]) && empty($_GET["destinoforward".$i]))
                    {
                        $gestor = popen('sudo iptables -A FORWARD -j '.$_GET["acceptodropforward".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (empty($_GET["origenforward".$i]) && !empty($_GET["destinoforward".$i]))
                    {
                        $gestor = popen('sudo iptables -A FORWARD -s '.$_GET["origenforward".$i].' -j '.$_GET["acceptodropforward".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (!empty($_GET["origenforward".$i]) && empty($_GET["destinoforward".$i]))
                    {
                        $gestor = popen('sudo iptables -A FORWARD -d '.$_GET["destinoforward".$i].' -j '.$_GET["acceptodropforward".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else
                    {
                        $gestor = popen('sudo iptables -A FORWARD -s '.$_GET["origenforward".$i].' -d '.$_GET["destinoforward".$i].' -j '.$_GET["acceptodropforward".$i].' ', 'r');
                        pclose($gestor);
                    }
                    
                }
            }




            for ($i=1; $i <= $_GET["trhayoutput"]; $i++) 
            { 
                if (!empty($_GET["acceptodropoutput".$i]))
                {
                    if (empty($_GET["origenoutput".$i]) && empty($_GET["destinooutput".$i]))
                    {
                        $gestor = popen('sudo iptables -A OUTPUT -j '.$_GET["acceptodropoutput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (empty($_GET["origenoutput".$i]) && !empty($_GET["destinooutput".$i]))
                    {
                        $gestor = popen('sudo iptables -A OUTPUT -s '.$_GET["origenoutput".$i].' -j '.$_GET["acceptodropoutput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else if (!empty($_GET["origenoutput".$i]) && empty($_GET["destinooutput".$i]))
                    {
                        $gestor = popen('sudo iptables -A OUTPUT -d '.$_GET["destinooutput".$i].' -j '.$_GET["acceptodropoutput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    else
                    {
                        $gestor = popen('sudo iptables -A OUTPUT -s '.$_GET["origenoutput".$i].' -d '.$_GET["destinooutput".$i].' -j '.$_GET["acceptodropoutput".$i].' ', 'r');
                        pclose($gestor);
                    }
                    
                }
            }


            $gestor = popen('sudo iptables -I INPUT 1  -j LOG --log-prefix "iptables: "', 'r');
            pclose($gestor);

            $gestor = popen('sudo iptables -I FORWARD 1  -j LOG --log-prefix "iptables: "', 'r');
            pclose($gestor);

            $gestor = popen('sudo iptables -I OUTPUT 1  -j LOG --log-prefix "iptables: "', 'r');
            pclose($gestor);

            $gestor = popen('sudo sh bat/guardariptables.bat', 'r');
            pclose($gestor);

            echo '<meta http-equiv="refresh" content="0; url = firewall.php"/>';
            
        }

?>