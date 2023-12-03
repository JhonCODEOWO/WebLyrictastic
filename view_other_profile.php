<?php
include('includes/templates/header.php');
require('includes/config/db.php');

$db = conectarDB();
$id_profile = $_GET['id_profile'];

$query = "SELECT * FROM usuario WHERE id = $id_profile";
$resultado = mysqli_fetch_assoc(mysqli_query($db, $query));

$queryLetras = "SELECT * FROM letras WHERE usuario_id = $id_profile";
$resultadoLetras = mysqli_query($db, $queryLetras);

?>

<main class="flex-default vw_profile">
    <section class="centrar-texto vw_profile--info">
        <img class="vw_profile--imgUser" src="imagenes/user_imgs/<?php echo $resultado['foto'] ?>" alt="">
        <h2> <?php echo $resultado['alias'] ?> </h2>
        <h4> <?php echo $resultado['email'] ?> </h3>
            <p>Aqui va el texto de la descripción</p>
            <form action="" method="post">
                <button class="btn-advertesing btn-principal width-100" type="submit" value="<?php echo $resultado['id'] ?>">Seguir</button>
            </form>
    </section>

    <section class="vw_profile--showLetras">
        <h2 class="uppercase no-margin-bottom"> letras publicas </h2>
        <div class="vw_profile--letras">
            <?php foreach ($resultadoLetras as $letra) { ?>
                <div class="letra-tarjeta overlay-tarjeta">
                    <div class="letra-tarjeta-content-blur">
                        <a class="without-transition-a" href="lyric.php?id=<?php echo $letra['id'] ?>&s=index">
                            <img src="<?php echo  'imagenes/' . $letra['imagen'] ?>" alt="imagen_cancion">
                            <div class="overlay-letra">
                                <h3> <?php echo $letra['titulo'] ?> </h3>
                                <h3 class="light"> <?php echo $letra['artista_original'] ?></h3>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </section>

    <section>

    </section>
</main>

<?php
include('includes/templates/footer.php');
?>