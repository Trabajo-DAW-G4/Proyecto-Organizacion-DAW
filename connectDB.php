<?php

function connectDB(){
    $conexion = "mysql:dbname=tiendaropa;host=127.0.0.1";
    $user = "root";
    $password = "";

    try{
        $bd = new PDO($conexion, $user, $password);
        return $bd;
    } catch(PDOException $e){
        echo "Error al conectar con la base de datos." . $e->getMessage();
        return false;
    }
}
