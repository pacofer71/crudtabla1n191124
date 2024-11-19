<?php
namespace App\Db;
use \PDO;
use \PDOException;
class Articulo extends Conexion{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private int $categoria_id;

    public function create(): void{
        $q="insert into articulos(nombre, descripcion, categoria_id) values(:n, :d, :ci)";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':ci'=>$this->categoria_id
            ]);
        }catch(PDOException $ex){
            throw new PDOException("Error en crear: {$ex->getMessage()}", -1);
            
        }finally{
            parent::cerrarConexion();
        }
    }

    public static function crearArticulosRandom(int $cant): void{
        $faker=\Faker\Factory::create('es_ES');
        $categoriasId=Categoria::devolverArrayId();
        for($i=0; $i<$cant; $i++){
            $nombre=$faker->unique()->sentence(5);
            $descripcion=$faker->text();
            $categoria_id=$faker->randomElement($categoriasId)

        }
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of categoria_id
     */
    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     */
    public function setCategoriaId(int $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }
}