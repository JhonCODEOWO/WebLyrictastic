<?php 
    require "includes/config/db.php";
    $db = conectarDB();

    $alias = "";
    $contraseña = "";
    $errores = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $alias = $_POST["alias"];
        $contraseña = $_POST["contraseña"];

        if ($alias == "") {
            $errores[] = "Debes escribir un alias";
        }
        if ($contraseña == "") {
            $errores[] = "Escribe una contraseña";
        }

        $query = "SELECT * FROM usuario WHERE alias = '$alias'";
        $resultado = mysqli_query($db, $query);
        if ($resultado -> num_rows == 1) {
            $queryPassword = "SELECT contraseña FROM usuario WHERE alias = '$alias'";
            $resultadoContra = mysqli_query($db, $queryPassword);
            $contraObtenida = mysqli_fetch_assoc($resultadoContra);
            $verificado = password_verify($contraseña, $contraObtenida["contraseña"]);
            if ($verificado) {
                $usuario = mysqli_fetch_assoc($resultado);
                session_start();
                $_SESSION["alias"] = $usuario["alias"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["apellidos"] = $usuario["apellidos"];
                $_SESSION["autenticado"] = true;
                $_SESSION["id_user"] = $usuario["id"];
                $_SESSION["ruta_img"] = $usuario["foto"];
                header("Location: index.php");
            }else{
                $errores[] = "La contraseña ingresada es incorrecta";
            }
        }else{
            $errores[] = "El usuario ingresado no existe";
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lyrictastic - Iniciar sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="background-login">
    <main class="contenedor-login bg-difuminado bg-negro-transparente">
            <?php foreach($errores as $error){ ?>
                <div class="alerta">
                    <?php echo $error ?>
                </div>
            <?php } ?>
            <div class="caja-login">
                <div class="img-caja-login">
                    <!-- <img src="imgs/ui/login_img_aside.jpg" alt=""> -->
                </div>
                <div class="contenedor-form">
                    <form class="form-login" action="" method="POST">
                    <h1>Ingresa tu cuenta</h1>
                    <label for="alias">Alias de la cuenta</label>
                    <input type="text" name="alias" id="alias" placeholder="Escribe el identificador de tu cuenta">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" name="contraseña" id="contraseña" placeholder="Ingresa tu password">
                    <input class="btn-principal margin-top-elemento" type="submit" value="Iniciar sesión">
                </form>
                <div class="soporte-usuario">
                    <a href="create_acount.php">Crear cuenta nueva</a>
                    <a href="recuperar_cuenta.php">Olvidé mi contraseña</a>
                </div>
                </div>
            </div>
    </main>
</body>
</html>