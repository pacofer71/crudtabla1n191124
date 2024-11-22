<?php
session_start();

use App\Db\Articulo;


require __DIR__ . "/../vendor/autoload.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $articulo = Articulo::getArticuloById($id);
    $disponible = ($articulo->disponible == 'SI') ? "NO" : "SI";
    (new Articulo)
        ->setNombre($articulo->nombre)
        ->setDescripcion($articulo->descripcion)
        ->setCategoriaId($articulo->categoria_id)
        ->setDisponible($disponible)
        ->update($id);
    //$_SESSION['mensaje'] = "Camboada disponivilidad del articulo: " . $articulo->nombre;
    //header("Location:articulos.php");
    //die();
}
$articulos = Articulo::read();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-8">
    <h3 class="py-2 text-center text-xl">Listados de Artículos</h3>
    <div class="flex flex-row-reverse mb-2">
        <a href="nuevo.php" class="p-2 rounded-xl bg-green-500 hover:bg-green-700">
            <i class="fas fa-add mr-2"></i>NUEVO
        </a>
    </div>
    <!-- Tabla articulos -->
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        NOMBRE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        DESCRPCIÓN
                    </th>
                    <th scope="col" class="px-6 py-3">
                        CATEGORIA
                    </th>
                    <th scope="col" class="px-6 py-3">
                        DISPONIBLE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ACCIONES
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($articulos as $item) {
                    $color = ($item->disponible == 'SI') ? "bg-green-500" : "bg-red-500";
                    echo <<<TXT
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {$item->nombre}
                    </th>
                    <td class="px-6 py-4">
                        {$item->descripcion}
                    </td>
                    <td class="px-6 py-4">
                        {$item->nomcat}
                    </td>
                    <td class="px-6 py-4">
                        <form method='POST' action='articulos.php'>
                        <input type='hidden' name='id' value="{$item->id}" />
                        <button class='w-full p-2 rounded-xl font-bold text-white $color'>
                            {$item->disponible}
                        </button>
                        </form>
                    </td>
                    <td>
                        <form action='borrar.php' method='POST'>
                            <input type='hidden' name='id' value='{$item->id}' />
                            <a href="update.php?id={$item->id}">
                                <i class='fas fa-edit text-blue-500 hover:text-xl mr-2'></i>
                            </a>
                            <button type='submit' onclick="return confirm('¿Borrar Artículo?');">
                                <i class='fas fa-trash text-red-500 hover:text-xl'></i>
                            </button>
                        </form>
                    </td>
                </tr>
                TXT;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- FIn tabla articulos -->
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
            <script>
            Swal.fire({
                icon: "success",
                title: "{$_SESSION['mensaje']}",
                showConfirmButton: false,
                timer: 1500
            });
            </script>
            TXT;
        unset($_SESSION['mensaje']);
    }
    ?>
</body>

</html>