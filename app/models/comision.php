<?php
require_once 'crud.php';
require_once "logger.php";

class Comision extends Crud
{
  const TABLE='comisiones';
  public $pdo;
  public $logger;
  public $id;
  public $fecha;
  public $detalle;
  public $monto;
  public $idEmpleado;
  public $idLiquidacion;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
    $this->logger = new Logger();
  }

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (fecha, detalle, monto, id_empleado, id_liquidacion) VALUES (?,?,?,?,?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->fecha,
        $this->detalle,
        $this->monto,
        $this->idEmpleado,
        $this->idLiquidacion,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

  public function update(){}
}
?>
