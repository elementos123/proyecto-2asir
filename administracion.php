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
            <li><a href="administraruser.php">Administrar usuarios y rangos</a></li>
            <li><a href="cerrarsesion.php">Cerrar sesión</a></li>
        </ul>
    </nav>
    
    <br>
    
        <button class="button" onclick="CrearInputs();">Añadir nuevo usuario</button>

        <!-- Form with a input with name user -->
        <form id="formulario" method="get" action="">
        </form>
    <center>


    <script>
        var contador = 0;
        function CrearInputs()
        {
            var formulario = document.getElementById("formulario");
            if (document.getElementById("boton"))
            {
                document.getElementById("totales").remove();
                document.getElementById("boton").remove();
            }
            document.getElementById("formulario").innerHTML += "<input type='text' id='user"+contador+"' name='user"+contador+"' placeholder='Nombre de usuario'>";
            document.getElementById("formulario").innerHTML += "<input type='password' id='password"+contador+"' name='password"+contador+"' placeholder='Contraseña del usuario'>";
            document.getElementById("formulario").innerHTML += "<input type='email' id='email"+contador+"' name='email"+contador+"' placeholder='Correo electrónico del usuario'>";
            document.getElementById("formulario").innerHTML += "<input type='number' id='rango"+contador+"' name='rango"+contador+"' placeholder='Rango del usuario'>";
            document.getElementById("formulario").innerHTML += "<input id='eliminar"+contador+"' style='color:red; border:none;' onclick='EliminarInputYReorganizar("+contador+");' type='button' name='eliminar"+contador+"' value='X'><br id='br1"+contador+"'><br <br id='br2"+contador+"'>";
            var operacion = contador + 1;
            document.getElementById("formulario").innerHTML += "<input type='hidden' id='totales' name='totales' value='"+operacion+"'>";
            document.getElementById("formulario").innerHTML += "<input id='boton' type='submit' name='CrearUsuario' value='Crear usuarios'>";
            
            contador++;
        }



        function EliminarInputYReorganizar(numero)
        {
            var formulario = document.getElementById("formulario");
            document.getElementById("user"+numero).remove();
            document.getElementById("password"+numero).remove();
            document.getElementById("email"+numero).remove();
            document.getElementById("rango"+numero).remove();
            document.getElementById("eliminar"+numero).remove();
            document.getElementById("br1"+numero).remove();
            document.getElementById("br2"+numero).remove();
            var contadorreorganizador = 0;
            for (i=0; i < contador; i++)
            {
                if (document.getElementById("user"+i))
                {
                    document.getElementById("user"+i).setAttribute("name", "user"+contadorreorganizador);
                    document.getElementById("user"+i).setAttribute("id", "user"+contadorreorganizador);
                    document.getElementById("password"+i).setAttribute("name", "password"+contadorreorganizador);
                    document.getElementById("password"+i).setAttribute("id", "password"+contadorreorganizador);
                    document.getElementById("email"+i).setAttribute("name", "email"+contadorreorganizador);
                    document.getElementById("email"+i).setAttribute("id", "email"+contadorreorganizador);
                    document.getElementById("rango"+i).setAttribute("name", "rango"+contadorreorganizador);
                    document.getElementById("rango"+i).setAttribute("id", "rango"+contadorreorganizador);
                    document.getElementById("eliminar"+i).setAttribute("onclick", "EliminarInputYReorganizar("+contadorreorganizador+");");
                    document.getElementById("eliminar"+i).setAttribute("name", "eliminar"+contadorreorganizador);
                    document.getElementById("eliminar"+i).setAttribute("id", "eliminar"+contadorreorganizador);
                    document.getElementById("br1"+i).setAttribute("id", "br1"+contadorreorganizador);
                    document.getElementById("br2"+i).setAttribute("id", "br2"+contadorreorganizador);
                    contadorreorganizador++;
                }
                
            }
            contador--;
            document.getElementById("totales").setAttribute("value", contador);

        }

    </script>
</body>
</html>


<?php

if (isset($_GET["CrearUsuario"]))
{
    require 'funciones.php';

    for ($i=0; $i < $_GET["totales"]; $i++)
    { 
        if (!empty($_GET["user" . $i]) || !empty($_GET["password" . $i]) || !empty($_GET["email" . $i]) || !empty($_GET["rango" . $i]) ) 
        {
            if ($i == $_GET["totales"]-1)
            {
                CrearUsuarios($_GET["user$i"], $_GET["password$i"], $_GET["email$i"], $_GET["rango$i"], false, true, "administracion.php");
            }
            else
            {
                CrearUsuarios($_GET["user$i"], $_GET["password$i"], $_GET["email$i"], $_GET["rango$i"], false, false, "");
            }
            
        }
    }

    echo '<meta http-equiv="refresh" content="0; url = administracion.php"/>';
}
?>