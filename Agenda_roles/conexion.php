<?php 
    /* Necesitamos hacer la conexion a la base de datos */
    $localhost = "localhost:3306"; 
    $usuario = "root"; 
    $pw =""; 
    $database="agenda"; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("Conexion fallida"); 
    }else{
        echo "conexion existosa";
    }
?> 