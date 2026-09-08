<?php
require_once 'crud.php';
class Presupuesto extends Crud
{
  public $id;
  const TABLE='presupuesto';
  public  $pdo;
  public $monto;
  public $idClaseGasto;
  public $idCanal;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  
  public function getAllConCanalYClaseGasto(){
    try
    {
        $sql = "select
        p.id,
        c.id id_canal,
        c.nombre nombre_canal,
        gc.id id_clase_gasto,
        gc.nombre nombre_clase_gasto,
        p.monto
        from presupuesto p, canal c, gastos_clase gc
        where p.id_canal = c.id
        and p.id_clase_gasto = gc.id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getTotalByCanal($canal){
    try
    {
        $sql = "select sum(monto) total from presupuesto where id_canal = :canal ;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':canal' => $canal));
        if ($stm->rowCount() > 0) {
          $rs =  $stm->fetch(PDO::FETCH_ASSOC);
          return $rs['total'];
        } else {
          return 0;
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
      $sql = "INSERT INTO ".self::TABLE." (id_canal, id_clase_gasto, monto) VALUES (?, ?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->idCanal,
        $this->idClaseGasto,
        $this->monto,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET monto = :monto, id_clase_gasto = :idClaseGasto, id_canal = :idCanal WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':monto' => $this->monto,
        ':idClaseGasto' => $this->idClaseGasto,
        ':idCanal' => $this->idCanal,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function getTotalByClaseGasto($claseGasto){
      try
      {
          $sql = "select sum(monto) total from presupuesto where id_clase_gasto = :claseGasto ;";
          $stm = $this->pdo->prepare($sql);
          $stm->execute(array(':claseGasto' => $claseGasto));
          if ($stm->rowCount() > 0) {
            $rs =  $stm->fetch(PDO::FETCH_ASSOC);
            return $rs['total'];
          } else {
            return 0;
          }
    
      }
      catch (PDOException $e){
          error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
          die($e->getMessage());
      }
  }


}

 ?>
