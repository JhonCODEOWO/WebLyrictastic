<?php
include('includes/templates/header.php');
require('includes/config/db.php');
$db = conectarDB();

$queryUsers = "SELECT * FROM usuario";
$resultado = mysqli_query($db, $queryUsers);

?>

<div>
    <main class="contenedor">
        <div class="margin-bottom-elemento margin-top-contenedor">
            <h1 class="uppercase no-margin">Escritores</h1>
            <h3 class="no-margin lowercase">Sin ellos este sitio no sería lo mismo, sigue a tus favoritos</h5>
        </div>

        <?php foreach ($resultado as $perfil) { ?>
            <a href="view_other_profile.php?id_profile=<?php echo $perfil['id'] ?>" class="without-transition-a perfil-user">
                <img src="./imagenes/user_imgs/<?php echo $perfil['foto'] ?>" alt="">
                <div>
                    <h2> <?php echo $perfil['alias'] ?> </h2>
                    <h3> <?php echo $perfil['nombre'] . " " . $perfil['apellidos'] ?></h2>
                        <h3>Letras creadas: </h3>
                </div>
            </a>
        <?php } ?>

        <!-- <a class="without-transition-a perfil-user">
            <img src="./imagenes/user_imgs/fd53ca6568186bf3fbe44bafa02e7c1b.png" alt="">
            <div>
                <h2>Nombre de usuario</h2>
                <h3>Letras creadas: </h3>
            </div>
        </a> -->
    </main>

    <section class="">

    </section>
</div>

<?php
include('includes/templates/footer.php');
?>