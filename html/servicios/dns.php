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
    <title>Configuración del DNS</title>
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
                <input type="search" onkeyup="BuscarFormArchivos();"; id="buscadordeficheros" style=" width: 20%; text-align:center; outline:none; padding: 0.5%; border: solid 2px #CCF381; border-radius: 200px; background: none; color: #CCF381; font-size: 90%;" placeholder="Buscar por nombre de archivo">
                <br><br>
                <form action="" method="post">
                    <input type="search" name="crearnombrearchivo" style=" width: 20%; text-align:center; outline:none; padding: 0.5%; border: solid 2px #CCF381; border-radius: 200px; background: none; color: #CCF381; font-size: 90%;" placeholder="Nombre del archivo a crear"><input style="position:relative; left: 3%; text-align:center; outline:none; padding: 0.5%; border: solid 2px #CCF381; border-radius: 200px; background: none; color: #CCF381; font-size: 90%; cursor: pointer;" type="submit" name="crearnuevoarchivo" value="Crear archivo">
                </form>
                <?php

                    $arrFiles = scandir('/etc/bind');
                    for ($i=0; $i < count($arrFiles); $i++) 
                    { 
                        if (strpos($arrFiles[$i], 'db.') !== false ||  strpos($arrFiles[$i], 'named.') !== false) 
                        {
                            echo '<form action="" method="post" id="form'.$i.'" name="form'.$arrFiles[$i].'">';
                            echo '<br><br><span style="cursor:pointer;" onclick="MostrarTextareaconContenidoDelFichero(textarea'.$i.');"> Editar el archivo: '.$arrFiles[$i].'</span>';
                            $gestor = popen('sudo cat /etc/bind/'.$arrFiles[$i].' 2>&1', 'r');
                            $leer = fread($gestor, filesize('/etc/bind/'.$_POST["mostrarcontenidoarchivo"]));
                            echo '<div id="textarea'.$i.'" style="display:none;">';
                            echo '<br><br>';
                            echo '<input type="hidden" name="nombrefichero" value="'.$arrFiles[$i].'">';
                            echo '<textarea name="contenidofichero" onkeydown="insertTab(this, event);" spellcheck="false" style="width: 30%; height: 300px; outline: none; padding: 1%;">'.$leer.'</textarea>';
                            echo '<br><br>';
                            echo '<input type="submit" value="Aplicar cambios" name="aplicarcambiosalfichero" >';
                            echo '</div>';
                            echo '</form>';
                        }
                    }
                ?>
            </div>
            

        <script>
            function MostrarTextareaconContenidoDelFichero(id)
            {
                if (document.getElementById(id.id).style.display == "block")
                {
                    document.getElementById(id.id).style.display = "none";
                }
                else
                {
                    document.getElementById(id.id).style.display = "block";
                }
                
            }


            function BuscarFormArchivos() 
            {
                var search = document.getElementById("buscadordeficheros").value;
                var todosformsconficheros = document.getElementsByTagName("form");
                for (let i = 0; i < todosformsconficheros.length; i++) 
                {
                    if (todosformsconficheros[i].name == 'form'+search)
                    {
                        document.getElementById(todosformsconficheros[i].id).style.display = "block";
                    }
                    else
                    {
                        document.getElementById(todosformsconficheros[i].id).style.display = "none";
                    }
                }

                if (search == "")
                {
                    for (let i = 0; i < todosformsconficheros.length; i++) 
                    {
                        document.getElementById(todosformsconficheros[i].id).style.display = "block";
                    }
                }
            }


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
        $gestor = popen('echo "'.$_POST["contenidofichero"].'" > textodns.txt 2>&1', 'r');
        pclose($gestor);
        $gestor = popen('sudo chown www-data:www-data /etc/bind/'.$_POST["nombrefichero"].'', 'r');
        pclose($gestor);
        $gestor = popen('sudo chmod 775  /etc/bind/'.$_POST["nombrefichero"].' ', 'r');
        pclose($gestor);
        $gestor = popen('sudo cat textodns.txt > /etc/bind/'.$_POST["nombrefichero"].'', 'r');
        pclose($gestor);
        $gestor = popen('sudo systemctl restart bind9', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="0; url = dns.php"/>';
    }
    else if(isset($_POST["crearnuevoarchivo"]))
    {
        $gestor = popen('cat /etc/bind/db.local > /etc/bind/'.$_POST["crearnombrearchivo"].'   ', 'r');
        pclose($gestor);
        $gestor = popen('sudo chown www-data:www-data /etc/bind/'.$_POST["crearnombrearchivo"].'', 'r');
        pclose($gestor);
        $gestor = popen('sudo chmod 770 /etc/bind/'.$_POST["crearnombrearchivo"].'', 'r');
        pclose($gestor);
        echo '<meta http-equiv="refresh" content="0; url = dns.php"/>';
    }

?>