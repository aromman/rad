<?php
require_once 'crud.php';
class ProductoUbicacion extends Crud
{
  public $id;
  const TABLE='productos_ubicacion';
  public  $pdo;
  public $idCanal;
  public $idNivelTipo01;
  public $nivelCodigo01;
  public $idNivelTipo02;
  public $nivelCodigo02;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  public function getAllDisponiblesParaAsignar(){
    try
    {
        $sql = "select
        pu.id id,
        concat(c.nombre, ' - ', put1.nombre, ' ', pu.nivel_codigo_01, ' - ', put2.nombre, ' ', pu.nivel_codigo_02 ) nombre
        from productos_ubicacion pu,
        canal c,
        productos_ubicacion_tipo put1,
        productos_ubicacion_tipo put2
        where pu.id_canal = c.id
        and pu.id_nivel_tipo_01 = put1.id
        and pu.id_nivel_tipo_02 = put2.id
        and pu.id not in (select distinct psk.id_ubicacion from productos_stock psk)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllConLabelParaSelector(){
    try
    {
        $sql = "select
        pu.id id,
        concat(c.nombre, ' - ', put1.nombre, ' ', pu.nivel_codigo_01, ' - ', put2.nombre, ' ', pu.nivel_codigo_02 ) nombre
        from productos_ubicacion pu,
        canal c,
        productos_ubicacion_tipo put1,
        productos_ubicacion_tipo put2
        where pu.id_canal = c.id
        and pu.id_nivel_tipo_01 = put1.id
        and pu.id_nivel_tipo_02 = put2.id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllConCanalYNiveles(){
    try
    {
        $sql = "select
        pu.*,
        c.nombre canal,
        put01.nombre nivel01,
        put02.nombre nivel02
        from
        productos_ubicacion pu,
        canal c,
        productos_ubicacion_tipo put01,
        productos_ubicacion_tipo put02
        where
        pu.id_canal = c.id
        and pu.id_nivel_tipo_01 = put01.id
        and pu.id_nivel_tipo_02 = put02.id
        order by pu.id_canal";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getLabelById($id){
    try
    {
        $sql = "select 
                concat(c.nombre, ' - ', put1.nombre, ' ', pu.nivel_codigo_01, ' - ', put2.nombre, ' ', pu.nivel_codigo_02 ) ubicacion
                from productos_ubicacion pu, 
                canal c,
                productos_ubicacion_tipo put1,
                productos_ubicacion_tipo put2
                where pu.id = $id
                and pu.id_canal = c.id
                and pu.id_nivel_tipo_01 = put1.id
                and pu.id_nivel_tipo_02 = put2.id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
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
    


/*Aquí Insertamos un animal, tenemos que crear forzosamente este método porque en el CRUD lo agregamos como **abstract** sino lo agregamos obtendremos un error.*/
  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (id_canal, id_nivel_tipo_01, nivel_codigo_01, id_nivel_tipo_02, nivel_codigo_02) VALUES (?, ?, ?, ?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->idCanal,
        $this->idNivelTipo01,
        $this->nivelCodigo01,
        $this->idNivelTipo02,
        $this->nivelCodigo02,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET id_canal = :idCanal, id_nivel_tipo_01 = :idNivelTipo01, nivel_codigo_01 = :nivelCodigo01, id_nivel_tipo_02 = :idNivelTipo02, nivel_codigo_02 = :nivelCodigo02 WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':idCanal' => $this->idCanal,
        ':idNivelTipo01' => $this->idNivelTipo01,
        ':nivelCodigo01' => $this->nivelCodigo01,
        ':idNivelTipo02' => $this->idNivelTipo02,
        ':nivelCodigo02' => $this->nivelCodigo02,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>