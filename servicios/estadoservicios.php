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
                <h2>Logs de iptables</h2>
                </div>
                <div class="modal-body">
                <textarea spellcheck="false" readonly style=" font-size: 80%; width: 100%; position:relative; left: -15px; margin:0; height: 700px; outline: none; padding: 1%;">
                    <?php 
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    error_reporting(E_ALL);
                        $gestor = popen("sudo cat /var/log/iptables.log 2>&1", "r");
                        $contenido = fread($gestor, filesize("/var/log/iptables.log"));
                        if (strlen($contenido) > 500000000) 
                        {
                            $gestor = popen('echo "" > /var/log/iptables.log', "w");
                        }
                        $contenido = fread($gestor, filesize("/var/log/iptables.log"));
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
                            echo $arraycontenedor[$_GET["numero"]];
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
                        if (isset($_GET["numero"]) && $_GET["numero"] < count($arraycontenedor)-1) 
                        {
                            $operacion = $_GET["numero"]+1;
                            echo '<input type="hidden" name="numero" id="campohide" value="'.$operacion.'">';
                        }
                        else if (isset($_GET["numero"]) && $_GET["numero"] >= count($arraycontenedor)-1)
                        {
                            $operacion = $_GET["numero"];
                            echo '<input type="hidden" name="numero" id="campohide" value="'.$operacion.'">';
                        }
                        else
                        {
                            echo '<input type="hidden" name="numero" id="campohide" value="2">';
                        }
                    ?>
                
                    <input type="submit" name="mostrarmasdeiptables" value="Mostrando <?php if (isset($_GET["numero"])) {echo $_GET["numero"];} else {echo "1";} ?> de <?php echo count($arraycontenedor)-1; ?>">
                    </form>
                </div>
                
            </div>

        </div>



        <div id="dns" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="dnsclose">&times;</span>
                <h2>Logs de dns</h2>
                </div>
                <div class="modal-body">
                <textarea spellcheck="false" readonly style=" font-size: 80%; width: 100%; position:relative; left: -15px; margin:0; height: 700px; outline: none; padding: 1%;">
                    <?php 
                        $gestor2 = popen("sudo cat /var/log/syslog | grep bind 2>&1", "r");
                        $contenido2 = fread($gestor2, filesize("/var/log/syslog"));
                        if (strlen($contenido2) > 500000000) 
                        {
                            $gestor2 = popen('echo "" > /var/log/syslog', "w");
                        }
                        $contenido2 = fread($gestor2, filesize("/var/log/syslog"));
                        $lineas2 = 0;
                        $contador2 = 0;
                        $array2 = explode("\n", $contenido2);
                        $arraycontenedor2 = array();
                        $contadorarray2 = 0;
                        $textotemporal2 = "";
                        for ($i=0; $i < count($array2); $i++) 
                        { 
                            $textotemporal2 = $textotemporal2 . $array2[$i]. "\n\n";

                            if ($contador2 == 10) 
                            {
                                $arraycontenedor2[$contadorarray2] = $textotemporal2;
                                $contador2 = 0;
                                $contadorarray2++;
                                $textotemporal2 = "";
                            }

                            $contador2++;
                            
                        }

                        if (isset($_GET["mostrarmasdedns"])) 
                        {
                            if($_GET["numerobind9"] < count($arraycontenedor2)-1)
                            {
                                echo $arraycontenedor2[$_GET["numerobind9"]];
                            }
                        }
                        else
                        {
                            echo $arraycontenedor2[0];
                        }
                    ?>
                    </textarea>
                    <?php 
                        
                        if (isset($_GET["mostrarmasdedns"])) 
                        {
                            if($_GET["numerobind9"] >= count($arraycontenedor2)-1)
                            {
                                $operacion2 = count($arraycontenedor2)-1;
                                echo '<meta http-equiv="refresh" content="0; url=estadoservicios.php?numerobind9=0&mostrarmasdedns=Mostrando+0+de+'.$operacion2.'"/>';
                            } 
                        }

                        if (isset($_GET["mostrarmasdedns"])) 
                        {
                            echo '<script>AbrirModal("dns", "dnsclose");</script>';
                        }

                    ?>
                    <form action="" method="get">
                    <?php
                        if (isset($_GET["numerobind9"]) && $_GET["numerobind9"] < count($arraycontenedor2)-1) 
                        {
                            $operacion2 = $_GET["numerobind9"]+1;
                            echo '<input type="hidden" name="numerobind9" id="campohide" value="'.$operacion2.'">';
                        }
                        else if (isset($_GET["numerobind9"]) && $_GET["numerobind9"] >= count($arraycontenedor2)-1)
                        {
                            $operacion2 = $_GET["numerobind9"];
                            echo '<input type="hidden" name="numerobind9" id="campohide" value="'.$operacion2.'">';
                        }
                        else
                        {
                            echo '<input type="hidden" name="numerobind9" id="campohide" value="1">';
                        }
                    ?>
                
                    <input type="submit" name="mostrarmasdedns" value="Mostrando <?php if (isset($_GET["numerobind9"])) {echo $_GET["numerobind9"];} else {echo "0";} ?> de <?php echo count($arraycontenedor2)-1; ?>">
                    </form>
                </div>
                
            </div>

        </div>



        <div id="dhcp" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="dhcpclose">&times;</span>
                <h2>Logs de dhcp</h2>
                </div>
                <div class="modal-body">
                <textarea spellcheck="false" readonly style=" font-size: 80%; width: 100%; position:relative; left: -15px; margin:0; height: 700px; outline: none; padding: 1%;">
                    <?php 
                        $gestor2 = popen("sudo cat /var/log/syslog | grep dhcpd 2>&1", "r");
                        $contenido2 = fread($gestor2, 999999);
                        if (strlen($contenido2) > 500000000) 
                        {
                            $gestor2 = popen('echo "" > /var/log/syslog', "w");
                        }
                        $contenido2 = fread($gestor2, filesize("/var/log/syslog"));
                        echo $contenido2;
                        $lineas2 = 0;
                        $contador2 = 0;
                        $array2 = explode("\n", $contenido2);
                        $arraycontenedor2 = array();
                        $contadorarray2 = 0;
                        $textotemporal2 = "";
                        for ($i=0; $i < count($array2); $i++) 
                        { 
                            $textotemporal2 = $textotemporal2 . $array2[$i]. "\n\n";

                            if ($contador2 == 20) 
                            {
                                $arraycontenedor2[$contadorarray2] = $textotemporal2;
                                $contador2 = 0;
                                $contadorarray2++;
                                $textotemporal2 = "";
                            }

                            $contador2++;
                            
                        }

                        if (isset($_GET["mostrarmasdedhcp"])) 
                        {
                            if($_GET["numerodhcp"] < count($arraycontenedor2)-1)
                            {
                                echo $arraycontenedor2[$_GET["numerodhcp"]];
                            }
                        }
                        else
                        {
                            $operacion2 = count($arraycontenedor2)-1;
                            echo $arraycontenedor2[0];
                        }
                    ?>
                    </textarea>
                    <?php 
                        
                        if (isset($_GET["mostrarmasdedhcp"])) 
                        {
                            if($_GET["numerodhcp"] >= count($arraycontenedor2))
                            {
                                $operacion2 = count($arraycontenedor2)-1;
                                $operacion3 = $operacion2+1;
                                echo '<meta http-equiv="refresh" content="0; url=estadoservicios.php?numerodhcp='.$operacion2.'&mostrarmasdedhcp=Mostrando+'.$operacion2.'+de+'.$operacion2.'"/>';
                            } 
                        }

                        if (isset($_GET["mostrarmasdedhcp"])) 
                        {
                            $operacion2 = count($arraycontenedor2)-1;
                            echo '<script>AbrirModal("dhcp", "dhcpclose");</script>';
                        }

                    ?>
                    <form action="" method="get">
                    <?php
                        if (isset($_GET["numerodhcp"]) && $_GET["numerodhcp"] < count($arraycontenedor2)-1) 
                        {
                            $operacion2 = $_GET["numerodhcp"]+1;
                            echo '<input type="hidden" name="numerodhcp" id="campohide" value="'.$operacion2.'">';
                        }
                        else if (isset($_GET["numerodhcp"]) && $_GET["numerodhcp"] >= count($arraycontenedor2)-1)
                        {
                            $operacion2 = $_GET["numerodhcp"];
                            echo '<input type="hidden" name="numerodhcp" id="campohide" value="'.$operacion2.'">';
                        }
                        else
                        {
                            echo '<input type="hidden" name="numerodhcp" id="campohide" value="1">';
                        }
                    ?>
                
                    <input type="submit" name="mostrarmasdedhcp" value="Mostrando <?php if (isset($_GET["numerodhcp"])) {echo $_GET["numerodhcp"];} else {echo "0";} ?> de <?php echo $operacion2; ?>">
                    </form>
                </div>
                
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