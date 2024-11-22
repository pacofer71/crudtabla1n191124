<?php

use App\Db\{Articulo,Categoria};
use App\Utils\Validaciones;

session_start();
$id=filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id || $id<=0){
    header("Location:articulos.php");
    exit();
}
require __DIR__ . "/../vendor/autoload.php";
$categorias = Categoria::read();
if(!$articulo=Articulo::getArticuloById($id)){
    header("Location:articulos.php");
    exit();
}
//para el campò de tipo check
$checkSi=($articulo->disponible=='SI') ? "checked" : "";
$checkNo=($articulo->disponible=='NO') ? "checked" : "";


if(isset($_POST['nombre'])){
    $nombre=Validaciones::sanearCadenas($_POST['nombre']);
    $descripcion=Validaciones::sanearCadenas($_POST['descripcion']);
    $categoria_id= (int) Validaciones::sanearCadenas($_POST['categoria_id']);
    $disponible=(isset($_POST['disponible'])) ? Validaciones::sanearCadenas($_POST['disponible']) : -1;
    $errores=false;
    if(!Validaciones::longitudCadenaValida('nombre', $nombre, 5, 40)){
        $errores=true;
    }else{
        if(Validaciones::existeNombre($nombre, $id)){
            $errores=true;
        }
    }
    if(!Validaciones::longitudCadenaValida('descripcion', $descripcion, 5, 250)){
        $errores=true;
    }
    if(!Validaciones::categoriaValida($categoria_id)){
        $errores=true;
    }
    if(!Validaciones::disponibleValido($disponible)){
        $errores=true;
    }
    if($errores){
        header("Location:update.php?id=$id");
        die();
    }
    //Todo ha ido bien
    (new Articulo)
    ->setNombre($nombre)
    ->setDescripcion($descripcion)
    ->setDisponible($disponible)
    ->setCategoriaId($categoria_id)
    ->update($id);
    
    $_SESSION['mensaje']="Se actualizó el artículo.";
    header("Location:articulos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-8">
    <h3 class="py-2 text-center text-xl">Editar Artículo</h3>
    <div class="w-1/2 mx-auto border-2 rounded-xl p-4 shadow-xl border-black">
        <form method="POST" action="update.php?id=<?=$id ?>">
            <div class="mb-4">
                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Artículo</label>
                <input type="text" id="nombre" name="nombre" value="<?=$articulo->nombre?>"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?php
                    Validaciones::pintarError('err_nombre'); 
                ?>
            </div>
            <div class="mb-4">
                <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción Artículo</label>
                <textarea id="descripcion" name="descripcion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"><?=$articulo->descripcion ?></textarea>
                <?php
                    Validaciones::pintarError('err_descripcion'); 
                ?>
            </div>
            <div class="mb-4">
                <label for="categoria_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría Artículo</label>
                <select id="categoria_id" name="categoria_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>___ Elige una categoría ___</option>
                    <?php
                    foreach ($categorias as $categoria) {
                        $cadena=($categoria->id==$articulo->categoria_id) ? "selected" : "";
                        echo "<option $cadena value='{$categoria->id}'>{$categoria->nombre}</option>";
                    }
                    ?>
                </select>
                <?php
                    Validaciones::pintarError('err_categoria_id'); 
                ?>
            </div>
            <div class="mb-4">
                <label for="disponible" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Disponible</label>
                <div class="flex">
                    <div class="flex items-center me-4">
                        <input id="SI" type="radio" value="SI"  <?=$checkSi ?> name="disponible" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="SI" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">SI</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="NO" type="radio" <?=$checkNo ?> value="NO" name="disponible" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="NO" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">NO</label>
                    </div>
                </div>
                <?php
                    Validaciones::pintarError('err_disponible'); 
                ?>
            </div>
            <div class="flex flex-row-reverse mb-2">
                <button type="submit" class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-edit mr-2"></i>EDITAR
                </button>
                <a href="articulos.php" class="mr-2 font-bold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-home mr-2"></i>VOLVER
                </a>
            </div>
        </form>

    </div>
</body>