<?php
session_start();
//Se usa autenticado para tener una variable inicializada y evitar errores, si existe en session el valor autenticado entonces se asigna el valor, en caso contrario se le asigna un valor null
$id_user = $_SESSION["id_user"] ?? null;
$autenticado = $_SESSION["autenticado"] ?? null;
$user_foto = $_SESSION["ruta_img"] ?? null;
$nombreUser = $_SESSION["nombre"] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lyrictastic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Francois+One&display=swap" rel="stylesheet">

    <link rel="preload" href="/LyrictasticWithBD/css/normalize.css" as="style">
    <link rel="preload" href="/LyrictasticWithBD/css/style.css" as="style">
    <link rel="stylesheet" href="/LyrictasticWithBD/css/normalize.css">
    <link rel="stylesheet" href="/LyrictasticWithBD/css/style.css">
    <link rel="icon" href="/LyrictasticWithBD/imgs/icon.ico">
</head>

<body>
    <header class="header">
        <div class="contenedor">
            <div class="header__contenido header-laptop">
                <div class="header__logo">
                    <a class="without-transition-a" href="/LyrictasticWithBD/index.php">
                        <h2>Nostalgic_<span class="negrita">LRCS</span></h2>
                    </a>
                </div>
                <nav class="header__nav">
                    <ul>
                        <a class="enlace_nav" href="/LyrictasticWithBD/discover.php">Descubrir</a>
                        <a class="enlace_nav" href="/LyrictasticWithBD/about_us.php">Contactanos</a>
                        <a href="/LyrictasticWithBD/users.php" class="enlace_nav">
                            Escritores
                        </a>
                        <?php if ($autenticado) { ?>
                            <div class="user-nav">
                                <a class="without-transition-a picture-profile-nav profile-info-tag" href="/LyrictasticWithBD/profile.php" id="btnProfile">
                                    <img style="width: 5rem; border-radius:50%;" src="/LyrictasticWithBD/imagenes/user_imgs/<?php echo $user_foto ?>" alt="">
                                </a>

                                <ul class="user-menu" id="ulOpcionesProfile">
                                    <a class="without-transition-a user-menu-link" href="/LyrictasticWithBD/profile.php"> <span class=" uppercase">Editar perfil</span> </a>
                                    <a class="without-transition-a user-menu-link" href="/LyrictasticWithBD/crud/index.php"> <span class=" uppercase">Crear letras</span> </a>
                                    <a class="without-transition-a user-menu-link" href="/LyrictasticWithBD/includes/config/cerrar_sesion.php"> <span class=" uppercase">Cerrar sesión</span> </a>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a class="enlace_nav" href="login.php"> <span class="negrita uppercase">Iniciar sesión</span> </a>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>