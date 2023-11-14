<?php

require 'app.php';

function incluirTemplate($nombreTemplate){
    include TEMPLATES_URL . "/{$nombreTemplate}.php";
    // echo TEMPLATES_URL . "/{$nombreTemplate}.php";
}