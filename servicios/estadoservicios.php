<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="css/firewall.css">
    <title>Estado Iptables, DHCP y DNS</title>
    <style>
        input[type="submit"]
        {
            background: none;
            border: none;
            cursor: pointer;
            margin: 3%;
            font-size: 80%;
        }


        /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        -webkit-animation-name: fadeIn; /* Fade in the background */
        -webkit-animation-duration: 0.4s;
        animation-name: fadeIn;
        animation-duration: 0.4s
        }

        /* Modal Content */
        .modal-content {
        position: fixed;
        bottom: 0;
        background-color: #fefefe;
        width: 100%;
        height: 100%;
        overflow: auto; /* Enable scroll if needed */
        -webkit-animation-name: slideIn;
        -webkit-animation-duration: 0.4s;
        animation-name: slideIn;
        animation-duration: 0.4s
        }

        /* The Close Button */
        .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
        }

        .close:hover,
        .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
        }

        .modal-header {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
        }

        .modal-body {padding: 2px 16px;}


        /* Add Animation */
        @-webkit-keyframes slideIn {
        from {bottom: -300px; opacity: 0} 
        to {bottom: 0; opacity: 1}
        }

        @keyframes slideIn {
        from {bottom: -300px; opacity: 0}
        to {bottom: 0; opacity: 1}
        }

        @-webkit-keyframes fadeIn {
        from {opacity: 0} 
        to {opacity: 1}
        }

        @keyframes fadeIn {
        from {opacity: 0} 
        to {opacity: 1}
        }



    </style>
</head>
<body>

        <script>
            function AbrirModal(id, idbuttonclose)
            {

                // Get the modal
                var modal = document.getElementById(id);

                // Get the <span> element that closes the modal
                var span = document.getElementById(idbuttonclose);

                modal.style.display = "block";

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

            }

        </script>


    <center>
        <header>
        
            <p>Configuración del Firewall</p>
            
        </header>
        <br><br>
        <nav style="border-bottom: solid 3px #CCF381; height: 80px;">
            <ul>
                <li style="left: 43%;"><a href="../home.php">Inicio</a></li>
            </ul>
        </nav>
        <br><br>
            <table>
                <tr>
                    <th>Nombre del servicio</th>
                    <th>Estado del servicio</th>
                    <th>Acciones</th>
                    <th>Ver registro del servicio</th>
                </tr>

                <form action="" method="post">
                <tr>
                    <td>Iptables</td>
                    <?php
                        $gestor = popen('sudo systemctl status iptables | grep Active: 2>&1', 'r');
                        $leer = fread($gestor, 4096);
                        echo '<td><input type="hidden" name="servicio" value="iptables">'.$leer.'</td>';
                    ?>
                    <td><input type="submit" name="Iniciar" value="&#9658;"><input type="submit" name="Parar" value="⬤"><input type="submit" name="Reiniciar" value="⟳"></td>
                    <td><span onclick="AbrirModal('iptables', 'iptablesclose');">Ver logs</span></td>
                </tr>
                </form>

                <form action="" method="post">
                <tr>
                    <td>Bind9</td>
                    <?php
                        $gestor = popen('sudo systemctl status bind9 | grep Active: 2>&1', 'r');
                        $leer = fread($gestor, 4096);
                        echo '<td><input type="hidden" name="servicio" value="bind9">'.$leer.'</td>';
                    ?>
                    <td><input type="submit" name="Iniciar" value="&#9658;"><input type="submit" name="Parar" value="⬤"><input type="submit" name="Reiniciar" value="⟳"></td>
                    <td><span onclick="AbrirModal('dns', 'dnsclose');">Ver logs</span></td>
                </tr>
                </form>

                <form action="" method="post">
                <tr>
                    <td>Isc-dhcp-server</td>
                    <?php
                        $gestor = popen('sudo systemctl status isc-dhcp-server | grep Active: 2>&1', 'r');
                        $leer = fread($gestor, 4096);
                        echo '<td><input type="hidden" name="servicio" value="isc-dhcp-server">'.$leer.'</td>';
                    ?>
                    <td><input type="submit" name="Iniciar" value="&#9658;"><input type="submit" name="Parar" value="⬤"><input type="submit" name="Reiniciar" value="⟳"></td>
                    <td><span onclick="AbrirModal('dhcp', 'dhcpclose');">Ver logs</span></td>
                </tr> 
                </form>
            </table>
        


        <div id="iptables" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="iptablesclose">&times;</span>
                <h2><?php echo "Logs de iptables"; ?></h2>
                </div>
                <div class="modal-body">
                <textarea spellcheck="false" readonly style=" font-size: 80%; width: 100%; position:relative; left: -15px; margin:0; height: 700px; outline: none; padding: 1%;">
                    <?php 
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    error_reporting(E_ALL);
                        $contenido = file_get_contents("/var/log/iptables.log");
                        if (strlen($contenido) > 500000) 
                        {
                            $gestor = popen('echo "" > /var/log/iptables.log', "w");
                        }
                        $contenido = file_get_contents("/var/log/iptables.log");
                        $lineas = 0;
                        $contador = 0;
                        $array = explode("\n", $contenido);
                        $arraycontenedor = array();
                        $contadorarray = 0;
                        $textotemporal = "";
                        for ($i=0; $i < count($array); $i++) 
                        { 
                            $textotemporal = $textotemporal . $array[$i]. "\n\n";

                            if ($contador == 10) 
                            {
                                $arraycontenedor[$contadorarray] = $textotemporal;
                                $contador = 0;
                                $contadorarray++;
                                $textotemporal = "";
                            }

                            $contador++;
                            
                        }

                        if (isset($_GET["mostrarmasdeiptables"])) 
                        {
                            echo $arraycontenedor[$_GET["numero"]+1];
                        }
                        else
                        {
                            echo $arraycontenedor[0];
                        }
                    ?>
                    </textarea>
                    <?php 

                        if (isset($_GET["mostrarmasdeiptables"])) 
                        {
                            echo '<script>AbrirModal("iptables", "iptablesclose");</script>';
                        }

                    ?>
                    <form action="" method="get">
                    <?php
                        if (isset($_GET["numero"]) && $_GET["numero"] < count($arraycontenedor)) 
                        {
                            echo "Aqui: " . count($arraycontenedor);
                            $operacion = $_GET["numero"]+1;
                            echo '<input type="hidden" name="numero" id="campohide" value="'.$operacion.'">';
                        }
                        else if (isset($_GET["numero"]) && $_GET["numero"] >= count($arraycontenedor))
                        {
                            echo "Aqui2: " . count($arraycontenedor);
                            $operacion = $_GET["numero"];
                            echo '<input type="hidden" name="numero" id="campohide" value="'.$operacion.'">';
                        }
                        else
                        {
                            echo "Aqui3: " . count($arraycontenedor);
                            echo '<input type="hidden" name="numero" id="campohide" value="2">';
                        }
                    ?>
                
                    <input type="submit" name="mostrarmasdeiptables" value="Mostrando <?php if (isset($_GET["numero"])) {echo $_GET["numero"];} else {echo "1";} ?> de <?php echo count($arraycontenedor); ?>">
                    </form>
                </div>
                
            </div>

        </div>



        <div id="dns" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="dnsclose">&times;</span>
                <h2>Logs de bind9</h2>
                </div>
                <div class="modal-body">
                <p>Some text in the Modal Body</p>
                </div>
            </div>

        </div>



        <div id="dhcp" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="dhcpclose">&times;</span>
                <h2><?php echo "Logs de isc-dhcp-server"; ?></h2>
                </div>
                <div class="modal-body">
                <p>Some text in the Modal Body</p>
                </div>
            </div>

        </div>
    </center>
</body>
</html>


<?php

    if (isset($_POST["Iniciar"]))
    {
        $gestor = popen('sudo systemctl start '.$_POST["servicio"].'', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="1; url = estadoservicios.php"/>';
    }
    else if (isset($_POST["Parar"]))
    {
        $gestor = popen('sudo systemctl stop '.$_POST["servicio"].'', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="1; url = estadoservicios.php"/>';
    }
    else if (isset($_POST["Reiniciar"]))
    {
        $gestor = popen('sudo systemctl restart '.$_POST["servicio"].'', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="1; url = estadoservicios.php"/>';
    }

?>