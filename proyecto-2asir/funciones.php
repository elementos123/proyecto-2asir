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

?>