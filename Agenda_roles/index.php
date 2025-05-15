<?php
    session_start(); 
    include_once "conexion.php"; 

    $conexion = new mysqli($localhost, $usuario, $pw, $database); 
    if($conexion->connect_error){
        die("Conexion fallida"); 
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["usuario"]) && isset($_POST["contra"])
            && isset($_POST["enviar"])){

                $usu = $_POST["usuario"]; 
                $contra = $_POST["contra"];

                $sql = $conexion->prepare("SELECT * FROM usuarios
                    WHERE Nombre = ? AND Clave = ?");
                
                $sql->bind_param("ss", $usu, $contra); 
                $sql->execute(); 
                $result = $sql->get_result(); 
                if($result->num_rows>0){
                    $fila = $result->fetch_assoc();
                    $id_usu = $fila["Codigo"];
                    $rol = $fila ["Rol"];

                    $_SESSION["usuario"] = $usu; 
                    $_SESSION["id_usu"] = $id_usu;
                    $_SESSION["rol"] = $rol; 

                    var_dump($id_usu, $rol);

                    header("Location: inicio.php"); 
                    exit();  
                }else{
                    $_SESSION["error"] = 1; 
                    header("Location: index.php"); 
                }



        }else{
            echo "No se han enviado los datos";
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <style>
        p{
            color: red; 
        }
    </style>
</head>
<body>
   <h2>AGENDA COMPLETA: EXAMEN</h2>
   <form action="" method="post">
        <label for="usuario">Usuario</label><br>
        <input type="text" name="usuario"><br>
        <label for="contra">Contraseña</label><br>
        <input type="password" name="contra" id=""><br>
        <button type="submit" name="enviar">Enviar</button>
   </form> 
   <?php
        if(isset($_SESSION["error"])){
            echo "<p>Credenciales incorrectas</p>";
        }
   ?>
</body>
</html>