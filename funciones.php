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
        //$gestor = popen('sh install/terminarinstalacion.bat 2>&1', 'r');
        //echo "'$gestor'; " . gettype($gestor) . "\n";
        //$leer = fread($gestor, 2096);
        //echo $leer;
        //pclose($gestor);
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

    session_start();
    $_SESSION["user"] = $usuario;
    header("location:home.php");
}


function ElaborarResultadosFirewall($texto, $nameinput, $valueinput)
{
    $rutaarchivoinput = "textos/input/input.txt";
    echo '<form action="" method="post">';
    echo '<h4>'.$texto.'<input type="submit" name="'.$nameinput.'" value="'.$valueinput.'"></h4>';
    echo '</form> ';
    echo '<hr style="border-color: #CCF381;">';

    popen('sudo iptables -S -v| grep INPUT > '.$rutaarchivoinput.' 2>&1', 'r');
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
    
    <table align="center"; style="text-align: center; border:solid 2px black;">
    <tr>
    <th style="text-align: center;">Posición</th>
    <th style="text-align: center;">Origen</th>
    <th style="text-align: center;">Destino</th>
    <th style="text-align: center;">ACCEPT O DROP</th>
    </tr>

    <?php
    $archivo = fopen("$rutaarchivoinput", "r");
    $contador = 0;
    $contadorultimohecho = 0;
    for ($i=0; $i < count($contenido); $i++) 
    { 
        $operacion = $i + 1;
        echo '<tr>';
        echo '<td style="text-align: center; padding-right: 3%;">'.$operacion.'</td>';
        while(!feof($archivo)) 
        {
            $line = fgets($archivo);
            $array = explode(" ", $line);
            if (strpos($line, "-A") !== false && $array[2] == "-s") 
            {
                
                echo '<td style="text-align: center; padding-right: 3%;">'.$array[3].'</td>';
                $contador = 0;
                break;
            }
            else if (strpos($line, "-A") !== false && $array[2] != "-s")
            {
                echo '<td style="text-align: center; padding-right: 3%;">No especificado, por defecto es todo</td>';
                $contador = 0;
                break;
            }
            $contador++;
        }
        fclose($file);

        $archivo2 = fopen("$rutaarchivoinput", "r");
        $contador2 = 0;
        while(!feof($archivo2)) 
        {
            $line2 = fgets($archivo2);
            $array2 = explode(" ", $line2);
            
            if (strpos($line2, "-A") !== false && $array2[4] == "-d") 
            {
                echo $array2[5];
                echo '<td style="text-align: center; padding-right: 3%;">'.$array2[5].'</td>';
                $contador2 = 0;
                break;
            }
            else if (strpos($line2, "-A") !== false && $array2[4] != "-d")
            {
                echo '<td style="text-align: center; padding-right: 3%;">No especificado, por defecto es todo</td>';
                $contador2 = 0;
                break;
            }
            $contador++;
        }
        fclose($file);
        echo '<td></td>';
        echo '</tr>';
    }
    echo '</table>';

    // $contenido obtiene las reglas input, 
    // simplemente poner dentro de alguna etiqueta p o span o div y 
    // hacer que se muevan con unas flechas que tendrán a los lados,
    // para posicionar las reglas a su antojo, de modo sencillo


}


?>