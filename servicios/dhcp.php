<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="css/firewall.css">
    <style>
        span, br
        {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome, Edge, Opera and Firefox */
        }

        input[type=text]::-ms-clear {  display: none; width : 0; height: 0; }
        input[type=text]::-ms-reveal {  display: none; width : 0; height: 0; }
        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-button,
        input[type="search"]::-webkit-search-results-decoration { display: none; }

    </style>
    <title>Configuración del DHCP</title>
</head>
<body>
    <center>
        <header>
            
            <p>Configuración del DNS</p>
                
        </header>
        <br><br>
        <nav style="border-bottom: solid 3px #CCF381; height: 80px;">
            <ul>
                <li style="left: 43%;"><a href="../home.php">Inicio</a></li>
            </ul>
        </nav>

        <div id="contenedor">
            <div id="archivos">
                <br>
                <?php

                    $gestor = popen('cat /etc/dhcp/dhcpd.conf', 'r');
                    $leer = fread($gestor, filesize('/etc/dhcp/dhcpd.conf'));
                    pclose($gestor);
                    echo '<form action="" method="post">';
                    echo '<textarea name="contenidofichero" onkeydown="insertTab(this, event);" spellcheck="false" style="width: 30%; height: 300px; outline: none; padding: 1%;">'.$leer.'</textarea>';
                    echo '<br><br>';
                    echo '<input type="submit" value="Aplicar cambios" name="aplicarcambiosalfichero" >';
                    echo '</form>';
                ?>
            </div>
            

        <script>
            function insertTab(o, e)
            {		
                var kC = e.keyCode ? e.keyCode : e.charCode ? e.charCode : e.which;
                if (kC == 9 && !e.shiftKey && !e.ctrlKey && !e.altKey)
                {
                    var oS = o.scrollTop;
                    if (o.setSelectionRange)
                    {
                        var sS = o.selectionStart;	
                        var sE = o.selectionEnd;
                        o.value = o.value.substring(0, sS) + "\t" + o.value.substr(sE);
                        o.setSelectionRange(sS + 1, sS + 1);
                        o.focus();
                    }
                    else if (o.createTextRange)
                    {
                        document.selection.createRange().text = "\t";
                        e.returnValue = false;
                    }
                    o.scrollTop = oS;
                    if (e.preventDefault)
                    {
                        e.preventDefault();
                    }
                    return false;
                }
                return true;
            }


        </script>

        </div>
    </center>
</body>
</html>


<?php

    if (isset($_POST["aplicarcambiosalfichero"]))
    {
        $gestor = popen('sudo chown www-data:www-data /etc/dhcp/dhcpd.conf', 'r');
        pclose($gestor);
        $gestor = popen('sudo chmod 775 /etc/dhcp/dhcpd.conf', 'r');
        pclose($gestor);
        $gestor = popen('echo "'.$_POST["contenidofichero"].'" > textodhcp.txt  | cat textodhcp.txt > /etc/dhcp/dhcpd.conf  2>&1', 'r');
        pclose($gestor);
        $gestor = popen('sudo systemctl restart isc-dhcp-server', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="0; url = dhcp.php"/>';
    }

?>