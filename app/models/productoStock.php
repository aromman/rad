<?php
require_once 'crud.php';
class ProductoStock extends Crud
{
  public $id;
  const TABLE='productos_stock';
  public  $pdo;
  public $idProducto;
  public $idEditorial;
  public $cantidad;
  public $idUbicacion;
  public $fecha;
  public $costo;
  public $nuevo;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  public function invalidarPorCanal($idCanal){
    try{
      $sql = "update productos_stock set validado=0 where id_ubicacion in (select id_ubicacion from productos_ubicacion where id_canal = :idCanal)";
      $stm = $this->pdo->prepare($sql);
      $stm->execute(array(':idCanal' => $idCanal));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function getByProducto($producto){
    try
    {
        $sql = "select ps.*, 
                e.nombre editorial,
                concat(c.nombre, ' - ', put1.nombre, ' ', pu.nivel_codigo_01, ' - ', put2.nombre, ' ', pu.nivel_codigo_02 )  ubicacion
                from productos_stock ps, editoriales e, productos_ubicacion pu, 
                canal c,
                productos_ubicacion_tipo put1,
                productos_ubicacion_tipo put2
                where ps.id_editorial = e.id
                and ps.id_ubicacion = pu.id
                and pu.id_canal = c.id
                and pu.id_nivel_tipo_01 = put1.id
                and pu.id_nivel_tipo_02 = put2.id
                and ps.id_producto = :producto";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':producto' => $producto));
        if ($stm->rowCount() > 0) {
          return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }
    
  public function getByProductoUbicacion($producto, $ubicacion){
    try
    {
        $sql = "select ps.*, 
                e.nombre editorial,
                concat(c.nombre, ' - ', put1.nombre, ' ', pu.nivel_codigo_01, ' - ', put2.nombre, ' ', pu.nivel_codigo_02 )  ubicacion,
                p.titulo nombre_producto
                from productos_stock ps, editoriales e, productos_ubicacion pu, 
                canal c,
                productos_ubicacion_tipo put1,
                productos_ubicacion_tipo put2,
                productos p
                where ps.id_editorial = e.id
                and ps.id_ubicacion = pu.id
                and pu.id_canal = c.id
                and pu.id_nivel_tipo_01 = put1.id
                and pu.id_nivel_tipo_02 = put2.id
                and ps.id_producto = p.id
                and ps.id_producto = :producto
                and ps.id_ubicacion = :ubicacion";
        //error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$sql, 3, "my-errors.log");        
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':producto' => $producto, ':ubicacion' => $ubicacion));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }
  

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (id_producto, id_editorial, cantidad, id_ubicacion, fecha, costo, nuevo) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->idProducto,
        $this->idEditorial,
        $this->cantidad,
        $this->idUbicacion,
        $this->fecha,
        $this->costo,
        $this->nuevo,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET id_producto = :idProducto, id_editorial = :idEditorial, cantidad = :cantidad, id_ubicacion = :idUbicacion, fecha = :fecha, costo = :costo, nuevo = :nuevo WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':idProducto' => $this->idProducto,
        ':idEditorial' => $this->idEditorial,
        ':cantidad' => $this->cantidad,
        ':idUbicacion' => $this->idUbicacion,
        ':fecha' => $this->fecha,
        ':costo' => $this->costo,
        ':nuevo' => $this->nuevo,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>
