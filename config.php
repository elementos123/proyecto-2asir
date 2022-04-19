<?php

function Conectar()
{
    $host = "localhost";
    $user = "root";
    $password = "Lolpx123@";
    $db = "test";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        print "Error:  " . $e->getMessage() . "<br/>";
        die();
    }
    return $pdo;   
}


function DevolverIpPfsense()
{
    $pfsense1 = "http://";
    $pfsense2 = "http://";
    
    return $pfsense1, $pfsense2;
}


?>
