<?php
include('includes/templates/header.php');
$typeErr = $_GET['error'];
$descError = "";
switch ($typeErr) {
    case 1:
        $descError = "El recurso al que estás intentando acceder no existe ERROR 404";
        break;
    case 2:
        $descError = "No puedes acceder a este recurso, incia sesión o crea una cuenta";
        break;
    default:
        # code...
        break;
}
?>

<main style="text-align: center;">
    <h2><?php echo $descError ?></h2>
    <img style="width: 20%;" src="imgs/Errores o advertencia/cara triste.png" alt="">
</main>

<?php
include('includes/templates/footer.php');
?>