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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["nombre"]) && isset($_POST["email"]) && isset($_POST["telefono"]) && isset($_POST["grabar"])){
            $nombres = $_POST["nombre"]; 
            $emails = $_POST["email"]; 
            $telefonos = $_POST["telefono"]; 

            $sql = $conexion->prepare("SELECT Nombre FROM usuarios
                WHERE Nombre =? AND Codigo=?
            "); 
            $sql->bind_param("si", $usu, $id_usu); 
                $sql->execute(); 
                $result = $sql->get_result(); 
                if($result->num_rows>0){
                   for ($i = 0; $i < count($nombres); $i++) {
                      $nombre = $nombres[$i];
                        $email = $emails[$i];
                        $telefono = $telefonos[$i];

                        // Evita grabar contactos vacíos
                        if (!empty($nombre) && !empty($email) && !empty($telefono)) {
                            $grabar = $conexion->prepare("INSERT IGNORE INTO contactos (nombre, email, telefono, codusuario) VALUES (?, ?, ?, ?)");
                            $grabar->bind_param("ssii", $nombre, $email, $telefono, $id_usu);
                            $grabar->execute();
                        }
                     }
                     header("Location: grabado.php");
                     exit();
                     echo "Se han grabado correctamente los contactos.";
                }else{
                    echo "No se ha grabado correctamente"; 
                }
        }
    }



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
    <h3>Hola <?php  echo $usu?></h3>
    <form method='POST' action=''>
    <?php
    for($i = 0; $i < $pulsaciones; $i++){
        echo "
            <h4>CONTACTO $i</h4>
            <label for='nombre_$i'>Nombre $i</label><br>
            <input type='text' name='nombre[]'><br>
            <label for='email_$i'>Email $i</label><br>
            <input type='email' name='email[]'><br>
            <label for='telefono_$i'>Telefono $i</label><br>
            <input type='number' name='telefono[]'><br><br>
        ";
    }
    ?>
    <button type="submit" name="grabar">Grabar</button>
</form>
</body>
</html>