<?php

use App\Db\Articulo;

session_start();
$id=filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if(!$id || $id<=0){
    header("Location:articulos.php");
    exit();
}
require __DIR__."/../vendor/autoload.php";
Articulo::delete($id);
$_SESSION['mensaje']="Se borró el artículo de código: $id";
header("Location:articulos.php");