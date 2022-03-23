<?php

if (isset($_POST["enviar"]))
{
    include 'funciones.php';
    $_POST["passadmin"] = password_hash($_POST["passadmin"], PASSWORD_BCRYPT);
    CrearUsuarios($_POST["useradmin"], $_POST["passadmin"], $_POST["emailadmin"], 'admin', true);
}

?>