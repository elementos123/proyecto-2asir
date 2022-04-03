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
    <style>
        td,th
        {
            padding: 1%;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"]
        {
            width: 100%;
            padding: 1%;
            border:none;
            border-bottom: solid 2px white;
            background: none;
            color: white;
            font-size: 70%;
            outline: none;
        }	
    </style>
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
            <li><a href="administracion.php">Crear usuarios</a></li>
            <li><a href="cerrarsesion.php">Cerrar sesión</a></li>
        </ul>
    </nav>
    
    <br>
    <form action="" method="get">
        <input type="submit" name="cambios" value="Actualizar cambios">
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Correo electrónico</th>
                <th>Rango</th>
                <th>Eliminar</th>
            </tr>

            <?php

                require 'funciones.php';
                $info = ObtenerInfoTodosLosUsuarios();
                for ($i=0; $i < count($info); $i++) 
                { 
                    echo "<tr>";
                    echo "<input type='hidden' name='useroriginal".$i."' value='".$info[$i]["usuario"]."'>";
                    echo "<td><input type='text' name='user".$i."' value='".$info[$i]["usuario"]."' placeholder='Nombre usuario' required></td>";
                    echo "<td><input type='password' name='password".$i."' value='' placeholder='Nueva contraseña'></td>";
                    echo "<td><input type='email' name='email".$i."' value='".$info[$i]["email"]."' placeholder='correo electrónico' required></td>";
                    echo "<td><input type='number' name='ranks".$i."' value='".$info[$i]["ranks"]."' placeholder='Rango'></td>";
                    echo '</form>';
                    echo '<form action="" method="post">';
                    echo "<input type='hidden' name='useroriginal' value='".$info[$i]["usuario"]."'>";
                    echo "<td><input type='submit' name='eliminar' value='X'></td>";
                    echo "</tr>";
                }

            ?>
        </table>
    </form>
    <center>

</body>
</html>


<?php

if (isset($_GET["cambios"])) 
{
    $info = ObtenerInfoTodosLosUsuarios();
    for ($i=0; $i < count($info); $i++) 
    { 
        $useroriginal = $_GET["useroriginal".$i];
        $user = $_GET["user".$i];
        $password = $_GET["password".$i];
        $email = $_GET["email".$i];
        $ranks = $_GET["ranks".$i];

        if ($password != "")
        {
            $password = password_hash($password, PASSWORD_BCRYPT);
            ActualizarUsuario($useroriginal, $user, $password, $email, $ranks);
        }
        else
        {
            ActualizarUsuario($useroriginal, $user, "", $email, $ranks);
        }
    }

    echo '<meta http-equiv="refresh" content="0; url = administraruser.php"/>';
}
else if (isset($_POST["eliminar"])) 
{
    $user = $_POST["useroriginal"];
    EliminarUsuario($user);
    echo '<meta http-equiv="refresh" content="0; url = administraruser.php"/>';
}

?>