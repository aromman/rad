<?php
require_once 'crud.php';
class MedioPago extends Crud
{
  private $id;
  const TABLE='medio_pago';
  public  $pdo;
  
  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  public function __set($name,$value){
    $this->$name=$value;
  }
  public function __get($name){
    return $this->$name;
  }

  public function create(){
    try{
      $fields = "nombre";
      $params = array(':nombre' => $this->nombre);

      if (!empty($this->cargoPorcentaje)){
        $fields .= ", cargo_porcentaje";
        $params[':cargoPorcentaje'] = $this->cargoPorcentaje;
      }

      $fields .= ", id_cuenta";
      $params[':idCuenta'] = $this->idCuenta;

      $fields .= ", id_canal";
      $params[':idCanal'] = $this->idCanal;

      $placeholders = implode(',', array_keys($params));
      $sql = "INSERT INTO ".self::TABLE." (".$fields.") VALUES (".$placeholders.")";
      $stm=$this->pdo->prepare($sql);
      $stm->execute($params);
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET nombre = :nombre, cargo_porcentaje = :cargoPorcentaje, id_cuenta = :idCuenta, id_canal = :idCanal WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':nombre' => $this->nombre,
        ':cargoPorcentaje' => $this->cargoPorcentaje,
        ':idCuenta' => $this->idCuenta,
        ':idCanal' => $this->idCanal,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function getConCuentaById($id){
    try
    {
        $sql = "select mp.*, c.id cuenta from medio_pago mp, cuentas c where mp.id_cuenta = c.id and mp.id = :id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':id' => $id));
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

  public function getAllConCuentaYCanal(){
    try
    {
        $sql = "select mp.id,
        mp.nombre,
        mp.cargo_porcentaje,
        c.nombre cuenta,
        ca.nombre canal
        from medio_pago mp, cuentas c, canal ca
        where mp.id_cuenta = c.id
        and mp.id_canal = ca.id
        order by mp.nombre";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllByCanal($canal,$orderBy){
    try
    {
        $allowedOrderBy = array(
          'id' => 'mp.id',
          'nombre' => 'mp.nombre',
          'nombre ASC' => 'mp.nombre ASC',
          'nombre DESC' => 'mp.nombre DESC'
        );
        if (!isset($allowedOrderBy[$orderBy])) {
          throw new InvalidArgumentException("Ordenamiento invalido: ".$orderBy);
        }

        $stm = $this->pdo->prepare("select mp.* from medio_pago mp, cuentas_canales cc where mp.id_cuenta = cc.id_cuenta and cc.id_canal = :canal ORDER BY ".$allowedOrderBy[$orderBy].";");
        $stm->execute(array(':canal' => $canal));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function getByCuenta($idCuenta){
    try
    {
        $stm = $this->pdo->prepare("select * from medio_pago where id_cuenta = :idCuenta limit 1");
        $stm->execute(array(':idCuenta' => $idCuenta));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        return null;
    }
  }


}

 ?>
