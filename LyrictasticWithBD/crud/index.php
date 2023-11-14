<?php
    include '../includes/funciones.php';
    incluirTemplate('header');
    //session_start();
    $name_user = $_SESSION["alias"] ?? null;
    $id_user = $_SESSION["id_user"] ?? null;
    require '../includes/config/db.php';
    $db = conectarDB();
    //Consultar letras
    if ($id_user) {
        $query = "SELECT * FROM letras WHERE usuario_id = '$id_user'";
        $resultado = mysqli_query($db, $query);
        $exist_rows = ($resultado -> num_rows > 0) ? true : false;
        // echo "<pre>";
        // var_dump($resultado);
        // echo "</pre>";
        // echo $resultado -> num_rows;
    }else{
        header("Location: ../error_page.php?error=2");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idLetra = $_POST['id'];
        //Borramos primero todos los comentarios y después la letra
        $queryComentarios = "DELETE FROM comentarios WHERE letras_id = $idLetra";
        $resultado = mysqli_query($db, $queryComentarios);
        $query = "DELETE FROM letras WHERE id = $idLetra";
        $resultado = mysqli_query($db, $query);
        header('Location: index.php');
    }
?>

    <main class="contenedor">
        <h1 class="no-margin-bottom">Bienvenido <?php echo $name_user ?> </h1>
        <p class="no-margin-top">Aquí podrás agregar y visualizar las letras que has agregado</p>
    </main>

    <section class="">
        <a href="navegacion/crear.php" class="without-transition-a btn-principal btn-crud-add">Agregar nueva letra</a>
        <?php if($exist_rows) {?>
        <table class="tabla-registros">
            <thead>
                <tr class="tabla-cabecera">
                    <td>Id del registro</td>
                    <td>Título de las letras</td>
                    <td>Carátula agreagada</td>
                    <td>Artista original</td>
                    <td>Acciones</td>
                </tr>
            </thead>

            <tbody>
                <?php foreach($resultado as $letra) {?>
                <tr>
                    <td><?php echo $letra['id']; ?></td>
                    <td><?php echo $letra['titulo']; ?></td>
                    <td><img src="../imagenes/<?php echo $letra['imagen'] ?>" alt=""></td>
                    <td> <?php echo $letra['artista_original']; ?> </td>
                    <td>
                        <form style="row-gap: 1rem;" class="flex-column" method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $letra['id']; ?>">
                            <input class="btn-warning btn-principal" type="submit" value="Eliminar">
                            <!-- <input class="btn-advertesing btn-principal" type="submit" value="Modificar"> -->
                        </form>
                        <a href="navegacion/update.php?id=<?php echo $letra['id'] ?>" class="btn-advertesing btn-principal without-transition-a">Modificar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Diseño para dispositivos moviles -->
        <div class="tabla-movil">
            <?php foreach($resultado as $letra) {?>
                <div class="tabla-movil-registro">
                    <img src="../imagenes/<?php echo $letra['imagen'] ?>" alt="">
                    <div class="flex-column flex-center-vertical-horizontal">
                        <p class="no-margin"><?php echo $letra['titulo']; ?></p>
                        <p class="no-margin"><?php echo $letra['artista_original']; ?></p>
                    </div>
                    <div class="flex-column flex-center-vertical-horizontal">
                        <form style="row-gap: 1rem;" class="flex-column" method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $letra['id']; ?>">
                            <input class="btn-warning btn-principal" type="submit" value="Eliminar">
                            <!-- <input class="btn-advertesing btn-principal" type="submit" value="Modificar"> -->
                            <a href="navegacion/update.php?id=<?php echo $letra['id'] ?>" class="btn-advertesing btn-principal without-transition-a">Modificar</a>
                        </form>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php } else { ?>
            <h2>¡No has agregado aún alguna letra!</h2>
        <?php } ?>
    </section>

<?php
    incluirTemplate('footer');
?>