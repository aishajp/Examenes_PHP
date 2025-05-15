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

        $informacion = $conexion -> prepare("SELECT u.Codigo, u.Nombre, COUNT(c.codcontacto) AS contactos
        FROM usuarios u
        LEFT JOIN contactos c ON u.Codigo = c.codusuario
        GROUP BY u.Codigo, u.Nombre
        

        ");
    //  $informacion->bind_param("i", $id_usu);
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
    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="gestion.php">Pincha aquí para la gestion por roles</a>
</body>
</html>