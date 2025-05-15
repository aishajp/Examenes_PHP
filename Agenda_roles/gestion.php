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
    $modo_editar = false; 

    $buscar = $conexion ->prepare("SELECT Rol FROM usuarios WHERE Rol = ?"); 
    $buscar -> bind_param("s", $rol);
    $buscar->execute();
     $result = $buscar->get_result(); 

     if($result -> num_rows>0){
        $fila = $result->fetch_assoc(); 
        $rol = $fila["Rol"];
     }else{
        echo "no existe el usuario con rol";
     }
    
    if(isset($_POST["ver"])){
         $informacion = $conexion -> prepare("SELECT u.Codigo, u.Nombre, COUNT(c.codcontacto) AS contactos
                FROM usuarios u
                LEFT JOIN contactos c ON u.Codigo = c.codusuario
                GROUP BY u.Codigo, u.Nombre
               /* HAVING contactos>2 */
                ");
                    //$informacion->bind_param("i", $id_usu);
                $informacion->execute(); 
                $result = $informacion->get_result(); 
                $contactos =0; 
                if($result->num_rows>0){
                    echo "<table border='1' cellpadding='5' cellspacing='0'>";
                    echo "<tr><th>Codigo Usuario</th><th>Nombre</th><th>Numero de contactos</th></tr>";
                    while($fila = $result -> fetch_assoc()){
                        $codigoUsu = $fila["Codigo"];
                        $nombreUsu = $fila["Nombre"];
                        $contactos = $fila["contactos"];
                        echo "<tr>
                            <td>$codigoUsu</td>
                            <td>$nombreUsu</td>
                            <td>$contactos</td>
                        </tr>";
                }

                    echo "</table>";
            }
    }else if(isset($_POST["aniadir"])){
        header("Location: inicio.php"); 
        exit(); 
    }else if(isset($_POST["editar"])){
        echo '
            <form method="POST">
                <label for="Usuario">Buscar por codigo de usuario: </label>
                <input type="number" name="usuario"/>
                <button type="submit" name="enviar">Buscar</button>
            </form>
        ';
       

    }
        if(isset($_POST["enviar"])){
            if(isset($_POST["usuario"])){
                 $codigo = $_POST["usuario"];

                 $buscar = $conexion->prepare("SELECT Nombre, Codigo FROM usuarios WHERE Codigo=?"); 
                 $buscar -> bind_param("s", $codigo); 
                 $buscar-> execute(); 
                 $result = $buscar->get_result();
                 if($result -> num_rows>0){
                    $fila = $result->fetch_assoc(); 
                    $nombre = $fila["Nombre"];
                   echo "<p>Usuario encontrado: $codigo, $nombre</p>";  
                   $datos = $conexion->prepare("SELECT * FROM contactos WHERE codusuario  = (
                        SELECT Codigo FROM usuarios WHERE Codigo = ?)
                   ");
                   $datos -> bind_param("i", $codigo); 
                   $datos -> execute();
                   $result = $datos->get_result(); 
                   if($result -> num_rows>0){
                  
                    
                          
                   while  ($fila = $result->fetch_assoc()){
                    $id_contacto = $fila["codcontacto"];
                    $nombre_contacto = $fila["nombre"];
                    $email = $fila["email"];
                    $telefono = $fila["telefono"];
                        echo '
                            <form method="POST">
                                <input type="hidden" name="id_contacto" value="' . $id_contacto . '"/>
                                <label>Nombre del contacto:</label>
                                <input type="text" name="nombre_contacto" value="' . $nombre_contacto . '"/><br>
                                <label>Email:</label>
                                <input type="email" name="email" value="' . $email . '"/><br>
                                <label>Teléfono:</label>
                                <input type ="text" name="telefono" value="' . $telefono . '"/><br>
                                <input type="submit" name="guardar_contacto" value="Guardar Cambios"/>
                            </form>
                            <hr>
                        ';
                

                   }
                }     
                    
                   }else{
                    echo"no se han encontrado datos.";
                   }

                 }else{
                    echo "No se encuenta el usuario"; 
                 }
        }
           
          if(isset($_POST["guardar_contacto"])){
                        if(isset($_POST["id_contacto"],$_POST["nombre_contacto"], $_POST["email"], $_POST["telefono"], $_POST["guardar_contacto"])){
                            $actualizar = $conexion -> prepare("UPDATE contactos SET nombre=?, email=?, telefono=? WHERE codcontacto=?");
                            $actualizar->bind_param("sssi", $_POST["nombre_contacto"], $_POST["email"], $_POST["telefono"], $_POST["id_contacto"]);
                            if($actualizar->execute()){
                                if($actualizar->affected_rows>0){
                                    echo "<p>Actualizado</p>";
                                    
                                }else{
                                     echo "<p>Error</p>";
                                }
                            }else{
                                echo "error al actualizar";
                            }



                        }
                }
                
     
if(isset($_POST["accion"])){
     echo '
            <form method="POST">
                <label for="Usuario">Buscar por codigo de usuario: </label>
                <input type="number" name="usuario"/>
                <button type="submit" name="enviarBorrado">Buscar</button>
            </form>
        ';
}
if(isset($_POST["enviarBorrado"])){
    if(isset($_POST["usuario"])){
                 $codigo = $_POST["usuario"];

                 $buscar = $conexion->prepare("SELECT Nombre, Codigo FROM usuarios WHERE Codigo=?"); 
                 $buscar -> bind_param("i", $codigo); 
                 $buscar-> execute(); 
                 $result = $buscar->get_result();
                 if($result -> num_rows>0){
                    $fila = $result->fetch_assoc(); 
                    $nombre = $fila["Nombre"];
                   echo "<p>Usuario encontrado: $codigo, $nombre</p>";  
                   $datos = $conexion->prepare("SELECT * FROM contactos WHERE codusuario  = (
                        SELECT Codigo FROM usuarios WHERE Codigo = ?)
                   ");
                   $datos -> bind_param("i", $codigo); 
                   $datos -> execute();
                   $result = $datos->get_result(); 
                   if($result -> num_rows>0){
                    
                   while  ($fila = $result->fetch_assoc()){
                    $id_contacto = $fila["codcontacto"];
                    $nombre_contacto = $fila["nombre"];
                    $email = $fila["email"];
                    $telefono = $fila["telefono"];
                        echo '
                            <form method="POST">
                                <input type="hidden" name="id_contacto" value="' . $id_contacto . '"/>
                                <label>Nombre del contacto:</label>
                                <input type="text" name="nombre_contacto" value="' . $nombre_contacto . '"/><br>
                                <label>Email:</label>
                                <input type="email" name="email" value="' . $email . '"/><br>
                                <label>Teléfono:</label>
                                <input type ="text" name="telefono" value="' . $telefono . '"/><br>
                                <input type="submit" name="borrar_contacto" value="Borrar contacto"/>
                            </form>
                            <hr>
                        ';
                

                   }
                }     
                    
                   }else{
                    echo"no se han encontrado datos.";
                   }

                 }else{
                    echo "No se encuenta el usuario"; 
                 }
}
if(isset($_POST["borrar_contacto"])){
                        if(isset($_POST["id_contacto"])){
                            $borrar = $conexion -> prepare("DELETE FROM contactos WHERE codcontacto=?");
                            $borrar->bind_param("i",$_POST["id_contacto"]);
                            $borrar->execute(); 
                             if($borrar->affected_rows > 0){
                                echo "<p>Contacto borrado exitosamente.</p>";
                            } else {
                                 echo "No se pudo borrar el contacto.";
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
    <?php
        if($rol === "admin"):

    ?>
    <p>Rol de administrador</p>
    <form action="" method="POST">
        <button type="submit" name="ver" value="ver">Ver</button>
        <button type="submit" name="aniadir" value="aniadir">Añadir</button>
        <button type="submit" name= "editar" value="editar">Editar</button>
        <button type="submit" name="accion" value="borrar">Borrar</button>
    </form>
    <a href="index.php">¿Iniciar sesion como usuario?</a>
    <?php elseif($rol === "usuario"):?>
        <p>Rol de Usuario: <?php echo $usu?></p>
        <h5>Mis contactos</h5>
        <?php
            $usuario = $conexion->prepare("SELECT nombre, email, telefono FROM contactos WHERE codusuario=?");
            $usuario->bind_param("i", $id_usu);
            $usuario->execute(); 
            $result = $usuario->get_result(); 
            if($result -> num_rows>0){
                while($fila = $result->fetch_assoc()){
                    $nombre = $fila["nombre"];
                    $email = $fila ["email"];
                    $telefono = $fila["telefono"]; 
                   
                    echo '
                    
                        <ul>
                            <li>'.$nombre.'</li>
                            <li>'.$email.'</li>
                            <li>'.$telefono.'</li>
                        </ul>
                    ';
                } 
                
            }
        
        
        ?>
        <a href="index.php">¿Iniciar sesion como administrador?</a>
        <!-- <a href=""></a>
        <a href=""></a> -->
    <?php endif; ?> 
</body>
</html>