<?php
    require_once "includes/classes/funciones.php";
    require_once "includes/classes/querys_for_users.php";

    require "includes/config/db.php";
    $db = conectarDB();
    
    // require 'includes/config/db.php';
    // $db = conectarDB();
    $imagen = "";
    $nombre = "";
    $apellidos = "";
    $alias = "";
    $contraseña = "";
    $email = "";
    $errores = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $imagen = $_FILES['foto-perfil'];
        $tipoImagen = $imagen['type'];
        $size = 5341987;
        $pesoAMB = round($imagen['size'] / 1000000, 2);
        $nombre = $_POST["usuario"];
        $apellidos = $_POST["apellidos"];
        $contraseña = $_POST["contraseña"];
        $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
        $email = $_POST["email"];
        $alias = $_POST["alias"];

        if (!$imagen['name']) {
            $errores[] = "Elegir una imagen es obligatorio";
        }

        if (!$imagen['size'] > $size) {
            $errores[] = "La imagen que has elegido es mayor a la soportada, elige una menor a 5mb";
        }

        if ($nombre == "") {
            $errores[] = "Coloca un nombre válido";
        }
        if ($apellidos == "") {
            $errores[] = "Coloca tus apellidos"; 
        }
        if ($contraseña == "") {
            $errores[] = "Coloca una contraseña"; 
        }
        if ($email == "") {
            $errores[] = "El email es obligatorio"; 
        }
        if ($alias == "") {
            $errores[] = "El alias es obligatorio";
        }
        
        if (empty($errores)) {
            $carpeta = "imagenes/user_imgs/";
            $nombreTmp = $imagen['tmp_name'];
            $imageType = $imagen['type'];
            $funciones = new funciones_gral();
            $funciones_server = new funciones_File_Server();

            //Subimos la imagen al servidor y guardamos el nombre de la imagen con su extensión
            $nombreImagen = $funciones_server -> subirImagen($carpeta, $nombreTmp, $imageType);
            $resultado = agregarUsuario($db, $nombre, $apellidos, $contraseña_hash, $email, $alias, $nombreImagen);

            if ($resultado ==  true) {
                header("Location: login.php");
            }else{
                $errores[] = "El alias o el correo que has escrito ya existe, intenta con otros";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lyrictastic - Crear cuenta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="background-login">
    <main class="contenedor-login bg-difuminado bg-negro-transparente create">
            <div class="caja-login create">
                <div class="img-caja-login create">
                    <!-- <img src="imgs/ui/login_img_aside.jpg" alt=""> -->
                </div>
                <?php foreach($errores as $error) { ?>
                    <div class="alerta">
                        <?php echo $error ?>
                    </div>
                <?php } ?>
                <div class="contenedor-form">
                    <form class="form-login" action="" method="POST" enctype="multipart/form-data">
                    <h1>Crear cuenta</h1>
                    <label for="foto-perfil">Imágen de perfil</label>
                    <div class="update-file">
                        <input class="file" type="file" name="foto-perfil" id="foto-perfil">
                        <button class="btn-upload-file">Seleccionar archivo...</button>
                    </div>
                    <div class="flex-default gap-normal space-between">
                        <div class="flex-column width-100">
                            <label for="usuario">Nombre</label>
                            <input type="text" name="usuario" id="usuario" placeholder="Escribe el nombre de usuario">
                        </div>

                        <div class="flex-column width-100">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" placeholder="Ingresa tus apellidos">
                        </div>
                    </div>
                    
                    <div class="flex-default gap-normal space-between">
                        <div class="flex-column width-100">
                            <label for="alias">Alias para la cuenta</label>
                            <input type="text" name="alias" id="alias" placeholder="Identificador de tu cuenta">
                        </div>

                        <div class="flex-column width-100">
                            <label for="contraseña">Contraseña</label>
                            <input type="password" name="contraseña" id="contraseña" placeholder="Ingresa tu password">
                        </div>
                    </div>
                    
                    <label for="email">Correo de recuperación</label>
                    <input type="email" name="email" id="email" placeholder="Ingresa un correo electrónico">
                    <input style="margin-bottom: 2rem;" class="btn-principal margin-top-elemento margin-bottom-elemento" type="submit" value="Crear cuenta">
                </form>
                </div>
            </div>
    </main>
</body>
</html>