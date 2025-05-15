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

    var_dump($usu, $id_usu, $rol);


    if(!isset($_SESSION["pulsaciones"])){
        $_SESSION["pulsaciones"] =0; 

    }
    if(isset($_POST["incrementar"])){
        if($_SESSION["pulsaciones"]<5){
            $_SESSION["pulsaciones"]++; 
        }else if($_SESSION["pulsaciones"]==5){
            header("Location: agenda.php");
        }
    }

     $emojis = ["OIP0.jfif", "OIP1.jfif", "OIP2.jfif", "OIP3.jfif", "OIP4.jfif"];
    if(isset($_POST["reset"])){
        $_SESSION["pulsaciones"]=0; 
    }
    if(isset($_POST["grabar"])){
        $_SESSION["pulsaciones"]; 
        header("Location: agenda.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <style>
        img{
            width: 180px;
            height: 150px;
            margin: 5px;
        }

    </style>
</head>
<body>
    <h2>AGENDA</h2>
    <h3>Hola, <?php echo $usuario?> cuantos contactos desear grabar?</h3>
    <h3>Puedes grabar entre 1 y 5. 
        Por cada pulsación en INCREMENTAR grabarás un usuario más.
    </h3>
    <h3>Cuando el numero sea el deseado pulsa GRABAR</h3>
    <?php
        for ($i = 0; $i < $_SESSION["pulsaciones"]; $i++) {
            echo "<img src='materiales/{$emojis[$i]}' alt='emoji' />";
        }
    ?>
    <form action="" method="post">
        <button type="submit" name="incrementar">incrementar</button>
        <button type="submit" name="grabar">Grabar</button>
        <button type="submit" name="reset">Resetear</button>
    </form>
</body>
</html>