<?php
    require 'includes/config/db.php';
    require 'includes/funciones.php';
    incluirTemplate('header');
    $db = conectarDB();

    $query = "SELECT * FROM letras ORDER BY  likes DESC LIMIT 5;";
    $resultado = mysqli_query($db, $query);
?>

    <main class="main-index">
        <div>
            <h1 class="no-margin">Encuentra tu música favorita en esta página</h1>
        </div>
        <div class="flex-default uppercase margin-top-contenedor text-align-justify main-index--descripcion">
            <div class="bg-difuminado bg-negro-transparente">
                <h4>Comparte tus canciones favoritas</h4>
                <p>Gnerando publicaciones desde tu perfil toda la comunidad podrá visualizar tus letras escritas</p>
            </div>
            <div class="bg-difuminado bg-negro-transparente">
                <h4>Comparte tus opiniones</h4>
                <p>Escribe comentarios a los creadores de las publicaciones para generar letras escritas de mejor calidad</p>
            </div>
            <div class="bg-difuminado bg-negro-transparente">
                <h4>Crea traducciones</h4>
                <p>¡Traduce música para la gente que no puede comprender los temas en su totalidad debido al idioma!</p>
            </div>
        </div>
    </main>

    <section class="letras-populares-contenedor">
        <div class="flex-default cancelresponsive align-items space-between space-between padding-contenedor-left-right">
            <h2>Letras más gustadas</h2>
            <a href="blogs.php" class="without-transition-a btn-enlace">Ver top de letras más valoradas > </a>
        </div>
        <div class="letras letras--index">
        <?php foreach($resultado as $letra) {?>
                <div class="letra-tarjeta overlay-tarjeta">
                    <div class="letra-tarjeta-content-blur">
                        <a class="without-transition-a" href="lyric.php?id=<?php echo $letra['id'] ?>">
                                <img src="<?php echo  'imagenes/'.$letra['imagen'] ?>" alt="imagen_cancion">
                                <div class="overlay-letra">
                            <h3> <?php echo $letra['titulo'] ?> </h3>
                            <h3 class="light">Artista</h3>
                            <h3 class="light">Escritor de la letra</h3>
                        </div>
                        </a>
                    </div>
                </div>
        <?php } ?>
        </div>
    </section>

    <section class="sponsor-me">
        <h2 class="no-margin">¿Te gustaría crear tus propias letras?</h2>
        <a href="register.html" class="btn-principal btn--sponsorme without-transition-a">Registrate ahora y comienza a crear contenido</a>
    </section>

    <section class="blog padding-contenedor-left-right">
        <div class="flex-default cancelresponsive align-items space-between space-between">
            <h2>Blog</h2>
            <a href="blogs.php" class="without-transition-a btn-enlace">Leer más entradas > </a>
        </div>
        <div class="blog__entradas">

        </div>
    </section>
<?php
    incluirTemplate('footer');
?>