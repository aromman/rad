<?php
require_once 'crud.php';
class Editorial extends Crud
{
  private $id;
  const TABLE='editoriales';
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

  public function getComprasVsVentasMesActual($precioReferencia){
    try
    {
        $sql = "select
        e.nombre,
        (select ROUND(sum((c.cantidad * c.precio_costo) / :precioReferencia1), 0) from compras c, productos p
                                            where c.id_producto = p.id
                                            and p.id_editorial = e.id
                                            and c.id_estado != 1
                                            and YEAR(c.fecha) = YEAR(CURRENT_DATE())
                                            AND MONTH(c.fecha) = MONTH(CURRENT_DATE())
        ) as total_compras,
        (select ROUND(sum((v.unidades * v.costo) / :precioReferencia2), 0) from ventas v, productos p
                                            where v.id_producto = p.id
                                            and p.id_editorial = e.id
                                            and YEAR(v.fecha) = YEAR(CURRENT_DATE())
                                            AND MONTH(v.fecha) = MONTH(CURRENT_DATE())
        ) as total_ventas
        from editoriales e
        where e.consignacion = false
        order by nombre";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':precioReferencia1' => $precioReferencia, ':precioReferencia2' => $precioReferencia));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllConProveedor(){
    try
    {
        $sql = "select
        e.*,
        p.nombre proveedor
        from editoriales as e LEFT JOIN proveedores p
        on e.id_proveedor = p.id
        order by e.nombre";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

/*Aquí Insertamos un animal, tenemos que crear forzosamente este método porque en el CRUD lo agregamos como **abstract** sino lo agregamos obtendremos un error.*/
  public function create(){
    try{
      $fields = "nombre";
      $params = array(':nombre' => $this->nombre);

      if (!empty($this->porcentaje)){
        $fields .= ", porcentaje";
        $params[':porcentaje'] = $this->porcentaje;
      }
      if (!empty($this->montoFijo)){
        $fields .= ", monto_fijo";
        $params[':montoFijo'] = $this->montoFijo;
      }
      if (!empty($this->idProveedor)){
        $fields .= ", id_proveedor";
        $params[':idProveedor'] = $this->idProveedor;
      }
      if (!empty($this->consignacion)){
        $fields .= ", consignacion";
        $params[':consignacion'] = $this->consignacion;
      }

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
      $params = array(
        ':nombre' => $this->nombre,
        ':porcentaje' => !empty($this->porcentaje) ? $this->porcentaje : null,
        ':montoFijo' => !empty($this->montoFijo) ? $this->montoFijo : null,
        ':idProveedor' => !empty($this->idProveedor) ? $this->idProveedor : null,
        ':consignacion' => !empty($this->consignacion) ? $this->consignacion : false,
        ':id' => $this->id,
      );
      $sql = "UPDATE ".self::TABLE." SET nombre = :nombre, porcentaje = :porcentaje, monto_fijo = :montoFijo, id_proveedor = :idProveedor, consignacion = :consignacion WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute($params);
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>