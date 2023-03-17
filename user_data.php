<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] = 'POST') {
    //Recibimos las variables del formulario logeo
    include 'union.php';
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
        $pass = $_POST['password'];


        //Hacemos la query para buscar si existe un usuario con estos datos
        $sql = "SELECT * FROM usuarios 
     WHERE nombres = '$user' AND contraseña ='$pass'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['logged'] = true;
            while ($row = $result->fetch_assoc()) {
                //Creamos una array $row con los resultados de la query del usuario
                $_SESSION['nombres'] = $row['nombres'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                $_SESSION['estado'] = $row['Estado'];
                $_SESSION['dni'] = $row['dni']; //primary key

            }
            
        } else {
            header("Location: logeo.php?fallo=true");
        }
    }
    // se incluye php donde evalua el tipo de usuario logeado
    include 'panel_type.php';
}
?>
<!-- se vincula el css de la tabla -->
<link rel="stylesheet" href="css/user_data.css">
<!-- Creacion de la tabla segun tipo de usuario logeado -->

<div class="container">
    <?php 
    if (isset($_SESSION['update'])) {
    echo "<p style='color:green; font-weight: bold;'>Datos Actualizados con exito</p>";
    unset($_SESSION['update']);
} 
?>
<table>
    <tr>
        <th>DNI</th>
        <th>Usuario</th>
        <th>Contraseña</th>
        <th>Dirección MAC</th>
        <th>Biometrica</th>
        <th>Foto del DNI</th>
        <th>Tipo de usuario</th>
        <th>Estado de Datos</th>
        <th>Actualización</th>
    </tr>

    <?php
    //contenido de la tabla
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $tr = "<tr>";
            switch ($row['Estado']) {
                case 'Completo':
                    $tr="<tr class='green'>";
                    break;
                case 'Incompleto':
                    $tr="<tr class='yellow'>";
                    break;
                case 'Vacio/Erroneo':
                    $tr="<tr class='red'>";
                    break;
                default:
                    $tr="<tr>";
                    break;
            }
            echo "$tr <td>" . $row['dni'] . "</td>" .
                "<td>" . $row['nombres'] . "</td>" .
                "<td>" . $row['contraseña'] . "</td>" .
                "<td name='estado'>" . $row['mac'] . "</td>" .
                "<td name='estado'>" . $row['biometrica'] . "</td>" .
                "<td name='estado'>" . $row['foto_dni'] . "</td>" .
                "<td>" . $row['tipo_usuario'] . "</td>" .
                "<td>" . $row['Estado'] . "</td>" .
                "<td id='update'>" . "<form action='update_user.php' method='post'>
                        <input type='hidden' name='modiuser' value='" . $row['nombres'] . "'>
                        <button class='option' type='submit'><span class='material-symbols-outlined'>
                        drive_file_rename_outline
                        </span></button></form>" . "</td> </tr>";
        }
    }
    
    ?>
</table>
<a href="logeo.php">
    <button class="btn">Cerrar Sesión</button>
</a>

</div>