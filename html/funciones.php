<?php

require 'config.php';

function CrearUsuarios($usuario, $password, $email, $rango, $crearindex)
{
    $conexion = Conectar();
    $consulta = $conexion->prepare("SELECT * FROM users");
    $consulta->execute();
    
    while($fila = $consulta->fetch())
    {
        if ($usuario == $fila['usuario'])
        {
            echo "Ese usuario ya existe en la base de datos";
            return false;
        }
        else if ($email == $fila['email'])
        {
            echo "Ese correo electrónico ya existe en la base de datos, asignado al usuario " . $fila["usuario"];
            return false;
        }
    }

    $conexion = null;
    $consulta = null;

    $conexion = Conectar();
    $consulta = $conexion->prepare("INSERT INTO users(usuario,password,email,ranks) VALUES ('$usuario','$password','$email','$rango')");
    $consulta->execute();
    if ($crearindex)
    {
        error_reporting(E_ALL);

        /* Añade redirección, por lo que podemos obtener stderr. */
        $gestor = popen('sh install/terminarinstalacion.bat 2>&1', 'r');
        //echo "'$gestor'; " . gettype($gestor) . "\n";
        //$leer = fread($gestor, 2096);
        //echo $leer;
        pclose($gestor);
    }
    header("location:index.php");
    

}


function ConsultarUsuarios($usuario, $password)
{   
    if (empty($usuario)) {echo "El usuario no puede estar vacio"; return false;}

    if (empty($password)) {echo "La contraseña no puede estar vacia"; return false;}
    
    $conexion = Conectar();
    $consulta = $conexion->prepare("SELECT usuario,password FROM users");
    $consulta->execute();
    
    while ($fila = $consulta->fetch()) 
    {
        
        if ($usuario != $fila['usuario'])
        {
            echo "Ese usuario no existe en la base de datos";
            return false;
        }
        else if (!password_verify($password, $fila["password"]))
        {
            echo "Esa no es la contraseña del usuario " . $fila["usuario"];
            return false;
        }
    }

    $_SESSION["user"] = $usuario;
    echo '<meta http-equiv="refresh" content="0; url = home.php"/>';
}


function ElaborarResultadosFirewall($texto, $nameinput, $valueinput, $tipo, 
$botonnamesubmit, $idparacontartr, $idparaeliminartr,
$posiciontd, $origentd, $destinotd, $acceptordroptd, $eliminarbotonx, $idparaocultar)
{
    echo "<div id='".$idparaocultar."' style='display: none;'>";
    $rutaarchivoinput = "textos/input/".$tipo.".txt";
    echo '<center>';
    echo '<h4>'.$texto.'<input type="submit" name="'.$nameinput.'" value="'.$valueinput.'" id="botonfirewall"></h4>';
    echo '</center>';
    echo '<hr style="border-color: #CCF381;">';
    popen('sudo iptables -S -v | grep '.strtoupper($tipo).' > '.$rutaarchivoinput.' 2>&1', 'r');
    $archivo = fopen($rutaarchivoinput, "r");
    $contenido = fread($archivo, filesize($rutaarchivoinput));
    
    $contenido = explode("\n", $contenido);

    for ($i=0; $i < count($contenido); $i++) 
    { 
        if (strpos($contenido, "-P") !== false)
        {
            unset($contenido[$i]);
            $contenido[$i] = $contenido[$i+1];
        }
    }
    
    for ($i=0; $i < count($contenido)+1; $i++) 
    { 
        if ($contenido[$i] == "" 
        || $contenido[$i] == " "
        || $contenido[$i] == null) 
        {
            unset($contenido[$i]);
        }
    }
    ?>
    <p onclick="CrearNuevaRegla('<?php echo $idparacontartr; ?>',
    '<?php echo $idparaeliminartr; ?>', 
    '<?php echo $posiciontd; ?>',
    '<?php echo $origentd; ?>',
    '<?php echo $destinotd; ?>',
    '<?php echo $acceptordroptd; ?>',
    '<?php echo $eliminarbotonx; ?>',
    '<?php echo $tipo; ?>');" id="botonnoboton">Crear nueva regla</p>
    <table align="center"; style="text-align: center; border:solid 2px #CCF381;" id="<?php echo $idparacontartr; ?>">
    <tr>
    <th style="text-align: center; width: 2%;">Posición</th>
    <th style="text-align: center; width: 40%;">Origen</th>
    <th style="text-align: center; width: 40%;">Destino</th>
    <th style="text-align: center; width: 30%;">ACCEPT o DROP</th>
    <th style="text-align: center; width: 2%;">Eliminar regla</th>
    </tr>

    <?php
    $archivo = fopen("$rutaarchivoinput", "r");
    $guardarorigen = "No especificado, por defecto es 0.0.0.0";
    $guardardestino = "No especificado, por defecto es 0.0.0.0";
    for ($i=0; $i < count($contenido); $i++) 
    { 
        $line = fgets($archivo);
        $array = explode(" ", $line);
        if (strpos($array[$i], "LOG") !== false || strpos($array[$i], "-P") !== false)
        {
            pass;
        }
        else
        {
            //$operacion = $i + 1;
            echo '<tr id="'.$idparaeliminartr.$i.'">';
            while(!feof($archivo)) 
            {
                $line = fgets($archivo);
                $array = explode(" ", $line);
                if (strpos($line, "-A") !== false)
                {
    
                    for ($e=0; $e < count($array); $e++) 
                    { 
                        if (strpos($array[$e], "-s") !== false) 
                        {
                            $guardarorigen = $array[$e+1];
                            break;
                        }
                    }
    
                    
    
                    for ($o=0; $o < count($array); $o++) 
                    { 
                        if (strpos($array[$o], "-d") !== false) 
                        {
                            $guardardestino = $array[$o+1];
                            break;
                        }
                    }
    
                    if (strpos($line, "ACCEPT") !== false)
                    {
                        $acceptordrop = "ACCEPT";
                    }
                    else
                    {
                        $acceptordrop = "DROP";
                    }
                    
                    ?>
                    <td><input type="number" onclick="CambiarInfoInputs(<?php echo $i; ?>, '<?php echo $posiciontd; ?>', '<?php echo $origentd; ?>', '<?php echo $destinotd; ?>', '<?php echo $acceptordroptd; ?>', '<?php echo $eliminarbotonx; ?>')" value="<?php echo $i; ?>" name="<?php echo $posiciontd.$i; ?>" id="<?php echo $posiciontd.$i; ?>" readonly></td>
                    <td><input type="text" value="<?php echo $guardarorigen; ?>" name="<?php echo $origentd.$i; ?>" id="<?php echo $origentd.$i; ?>"></td>
                    <td><input type="text" value="<?php echo $guardardestino; ?>" name="<?php echo $destinotd.$i; ?>" id="<?php echo $destinotd.$i; ?>"></td>
                    <td><input type="text" value="<?php echo $acceptordrop; ?>" name="<?php echo $acceptordroptd.$i; ?>" id="<?php echo $acceptordroptd.$i; ?>"></td>
                    <td><span onclick="EliminarRegla(<?php echo $idparaeliminartr.$i; ?>, '<?php echo $idparacontartr; ?>', '<?php echo $idparaeliminartr; ?>', '<?php echo $posiciontd; ?>', '<?php echo $origentd; ?>', '<?php echo $destinotd; ?>', '<?php echo $acceptordroptd; ?>', '<?php echo $eliminarbotonx; ?>', '<?php echo $tipo; ?>')" style="color:red; cursor:pointer;" id="<?php echo $eliminarbotonx.$operacion; ?>">X</span></td>
                    <?php
                    $guardarorigen = "No especificado, por defecto es 0.0.0.0";
                    $guardardestino = "No especificado, por defecto es 0.0.0.0";
                    break;
                }
            }
        }

        echo '</tr>';
    }

    echo '</table>';
    ?>

    <?php
    echo '<input type="hidden" name="trhay'.$tipo.'" id="trhay'.$tipo.'" value="0">';
    echo '<input type="hidden" name="tipo" id="tipo" value="'.$tipo.'">';
    //echo '</form>';
    echo "</div>";
}
?>


<script>

    var seleccionado = false;
    var numeroseleccionado = 0;

    function CambiarInfoInputs(id, posicion, origen, destino, acceptodrop, eliminarbotonx)
    {
        
            if (!seleccionado) 
            {
                seleccionado = true;
                numeroseleccionado = id;
                document.getElementById(posicion+""+id).style.background = "red";
                document.getElementById(posicion+""+id).style.boxShadow = "none";
            }

            else if (seleccionado && id == numeroseleccionado)
            {
                seleccionado = false;
                numeroseleccionado = 0;
                document.getElementById(posicion+""+id).style.background = "none";
                document.getElementById(posicion+""+id).style.boxShadow = "none";
            }
            

            if (seleccionado && id != numeroseleccionado)
            {
                var origenseleccionadoguardar = document.getElementById(origen+""+numeroseleccionado).value;
                var destinoseleccionadoguardar = document.getElementById(destino+""+numeroseleccionado).value;
                var acceptodropseleccionadoguardar = document.getElementById(acceptodrop+""+numeroseleccionado).value;


                var origencambiarpor = document.getElementById(origen+""+id);
                var destinocambiarpor = document.getElementById(destino+""+id);
                var acceptodropcambiarpor = document.getElementById(acceptodrop+""+id);
                

                document.getElementById(origen+""+numeroseleccionado).value = origencambiarpor.value;
                document.getElementById(destino+""+numeroseleccionado).value = destinocambiarpor.value;
                document.getElementById(acceptodrop+""+numeroseleccionado).value = acceptodropcambiarpor.value;


                origencambiarpor.value = origenseleccionadoguardar;
                destinocambiarpor.value = destinoseleccionadoguardar;
                acceptodropcambiarpor.value = acceptodropseleccionadoguardar;


                document.getElementById(posicion+""+numeroseleccionado).style.background = "none";
                seleccionado = false;
                numeroseleccionado = 0;
                
                
            }
    }

    function EliminarRegla(id, idtable, ideliminartr, idposicion, idorigen, iddestino, idacceptodrop, ideliminarbotonx, tipo)
    {
        var element = id;
        element.parentNode.removeChild(element);
        var table = document.getElementById(idtable);
        var rows = table.getElementsByTagName("tr");
        var contador = 1;
        for (var i = 1; i < rows.length+1; i++) 
        {
            var operacion = i + 1;
            if (document.getElementById(idposicion + "" + i) != null)
            {
                document.getElementById(idposicion + "" + i).setAttribute( "value", contador);
                document.getElementById(idposicion + "" + i).setAttribute( "name", idposicion + "" + contador);
                document.getElementById(idposicion + "" + i).setAttribute( "onclick", "CambiarInfoInputs('"+contador+"', '"+idposicion+''+contador+"', '"+idorigen+''+contador+"', '"+iddestino+''+contador+"', '"+idacceptodrop+''+contador+"', '"+ideliminarbotonx+''+contador+"')");
                document.getElementById(idposicion + "" + i).setAttribute( "id", idposicion + "" + contador);
                document.getElementById(ideliminartr + "" + i).setAttribute( "id", ideliminartr + "" + contador);

                document.getElementById(idorigen + "" + i).setAttribute( "id", idorigen + "" + contador);
                document.getElementById(iddestino + "" + i).setAttribute( "id", iddestino + "" + contador);
                document.getElementById(idacceptodrop + "" + i).setAttribute( "id", idacceptodrop + "" + contador);
                document.getElementById(ideliminarbotonx+ "" + i).setAttribute( "onclick", "EliminarRegla("+ideliminartr+''+contador+", '"+idtable+"', '"+ideliminartr+"', '"+idposicion+"', '"+idorigen+"', '"+iddestino+"', '"+idacceptodrop+"', '"+ideliminarbotonx+"', '"+tipo+"')");
                document.getElementById(ideliminarbotonx+ "" + i).setAttribute( "id", ideliminarbotonx + "" + contador);
                contador++;
            }
        }
        contador = 0;
        var table = document.getElementById(idtable);
        var rows = table.getElementsByTagName("tr");
        document.getElementById("trhay"+tipo).value=rows.length-1;
    }

    function CrearNuevaRegla(idtable, eliminartr, posicion, origen, destino, acceptodrop, eliminarbotonx, tipo)
    {
        var rowCount = 0;
        var table = document.getElementById(idtable);
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) 
        {
            if (rows[i].getElementsByTagName("td").length > 0) 
            {
                rowCount++;
            }
        }


        var parent = table;
        var tbody = parent.childNodes[1];
        var operacion = rowCount+1;
        tbody.innerHTML += "<tr style='background:rgba(255,0,0,0.7);' id='"+eliminartr+operacion+"'>";
        document.getElementById(eliminartr+operacion).innerHTML += "<td><input type='number' onclick='CambiarInfoInputs('"+operacion+"', '"+posicion+''+operacion+"', '"+origen+''+operacion+"', '"+destino+''+operacion+"', '"+acceptodrop+''+operacion+"', '"+eliminarbotonx+''+operacion+"')' value='"+operacion+"' name='"+posicion+""+operacion+"' id='"+posicion+""+operacion+"' readonly></td>  <td><input type='text' value='' name='"+origen+""+operacion+"' id='"+origen+""+operacion+"'></td>  <td><input type='text' value='' name='"+destino+""+operacion+"' id='"+destino+""+operacion+"'></td>  <td><input type='text' value='' name='"+acceptodrop+""+operacion+"' id='"+acceptodrop+""+operacion+"'></td>";

        document.getElementById(eliminartr+operacion).innerHTML +=  "<td><span style='color:#CCF381; cursor:pointer;' id='"+eliminarbotonx+""+operacion+"'>X</span></td>";

        document.getElementById(eliminarbotonx+ "" + operacion).setAttribute( "onclick", "EliminarRegla("+eliminartr+''+operacion+", '"+idtable+"', '"+eliminartr+"', '"+posicion+"', '"+origen+"', '"+destino+"', '"+acceptodrop+"', '"+eliminarbotonx+"', '"+tipo+"')");

        document.getElementById("trhay"+tipo).value=operacion;
 
    }

    function Mostrar_Ocultar(id)
    {
        if (id == "ocultarinput") 
        {
            document.getElementById("ocultarinput").style.display = "block";
            document.getElementById("ocultarforward").style.display = "none";
            document.getElementById("ocultaroutput").style.display = "none";
        }
        else if (id == "ocultarforward")
        {
            document.getElementById("ocultarinput").style.display = "none";
            document.getElementById("ocultarforward").style.display = "block";
            document.getElementById("ocultaroutput").style.display = "none";
        }
        else if (id == "ocultaroutput")
        {
            document.getElementById("ocultarinput").style.display = "none";
            document.getElementById("ocultarforward").style.display = "none";
            document.getElementById("ocultaroutput").style.display = "block";
        }
    }


    function Contartr(idtable, idtr)
    {
        var table = document.getElementById(idtable);

        try 
        {
            var rows = table.getElementsByTagName("tr");
            document.getElementById(idtr).value = rows.length-1;
        }
        catch{}
        
    }

    var primeravez = setInterval(() => {
        Contartr("cortartrinput", "trhayinput");
        Contartr("cortartrforward", "trhayforward");
        Contartr("cortartroutput", "trhayoutput");
        clearInterval(primeravez);
    }, 1000);
    

</script>

