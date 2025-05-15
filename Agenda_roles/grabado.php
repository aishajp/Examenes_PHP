<?php
    session_start(); 
    include_once "conexion.php"; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("Conexion fallida"); 
    }

    $usu = $_SESSION["usuario"]; 
    $id_usu = $_SESSION["id_usu"];
    $rol = $_SESSION["rol"]; 
    $pulsaciones = $_SESSION["pulsaciones"];
    var_dump($usu, $id_usu, $rol, $pulsaciones);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>AGENDA</h2>
    <h3>Hola <?php echo $usu?></h3>
    <p>Se han grabado <?php echo $pulsaciones?> contactos de <?php echo $usu?></p><br>
    <a href="index.php">Volver a Loguearse</a><br>
    <a href="inicio.php">Introducir más contactos para <?php echo $usu?></a><br>
    <a href="totales.php">Total de contactos guardados</a><br>
    <a href="gestion.php">Ver mis contactos: <?php echo $usu?></a>
</body>
</html>