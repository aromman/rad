<?php
require_once 'connection.php';

class EntityDeleter extends Connection
{
  private static $tablasPermitidas = array(
    'clientes',
    'canal',
    'compras',
    'editoriales',
    'empleados',
    'ventas',
    'equipo_empleado',
    'descuentos',
    'equipos',
    'gastos_clase',
    'productos_formato',
    'gastos',
    'medio_pago',
    'productos_stock',
    'productos_serie',
    'productos_ubicacion',
    'productos_ubicacion_tipo',
    'salario',
    'proveedores',
    'orden_compra',
    'productos',
    'presupuesto',
  );

  public $pdo;

  public function __construct(){
    parent::__construct();
    $this->pdo = parent::conexion();
  }

  public function eliminarPorEntidadYId($entityName, $id){
    if (!in_array($entityName, self::$tablasPermitidas, true)) {
        return false;
    }

    try {
        $sql = "DELETE FROM " . $entityName . " WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        return $stm->execute(array(':id' => $id));
    } catch (PDOException $e) {
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        return false;
    }
  }
}
?>
