<?php
require_once 'crud.php';
require_once "logger.php";
require_once dirname(__DIR__) . '/helpers/CronExpressionHelper.php';

class Compra extends Crud
{
  const TABLE='compras';
  public $pdo;
  public $logger;
  public $id;
  public $idOrdenCompra;
  public $fecha;
  public $idProducto;
  public $cantidad;
  public $precioLista;
  public $precioCosto;
  public $idEstado;
  public $idMedioPago;
  
  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
    $this->logger =  new Logger();
  }

  private function completarFechaEntregaCalculada($rows)
  {
    if (!is_array($rows)) {
      return $rows;
    }

    foreach ($rows as $key => $row) {
      $fechaOrden = isset($row['fecha']) ? new DateTime($row['fecha']) : new DateTime();
      $cronEntrega = isset($row['cron_entrega']) ? $row['cron_entrega'] : '';
      $proximaEntrega = CronExpressionHelper::proximaFecha($cronEntrega, $fechaOrden, 'Y-m-d');
      $rows[$key]['fecha_entrega_calculada'] = is_array($proximaEntrega) ? $proximaEntrega['fecha'] : null;
    }

    return $rows;
  }

  public function getResumenPorCanalYSerieMensual(){
    try
    {
        $sql = "select
        year(co.fecha) ano,
        month(co.fecha) mes,
        c.nombre canal,
        c.id id_canal,
        pf.nombre serie,
        sum(co.cantidad) unidades,
        sum(co.precio_costo	* co.cantidad) monto
        from compras co, productos p, editoriales e, canal c, productos_formato pf
        where co.id_producto = p.id
        and p.id_editorial = e.id
        and 1 = c.id
        and p.id_formato = pf.id
        group by year(co.fecha), month(co.fecha), c.nombre, pf.nombre
        order by 1 desc ,2 desc,3";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getResumenPorCanalYSerieMensual : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getUltimaFechaByEditorial($idEditorial){
    try
    {
        $sql = "select max(c.fecha) fecha
        from compras c, productos p
        where p.id = c.id_producto
        and p.id_editorial = :idEditorial";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idEditorial' => $idEditorial));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getUltimaFechaByEditorial : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getAllDetalleByEditorial($idEditorial){
    try
    {
        $sql = "select
        c.id id,
        c.fecha fecha,
        p.titulo producto,
        c.cantidad,
        c.precio_lista,
        c.precio_costo,
        (c.cantidad * c.precio_costo) costo_total,
        (c.cantidad * c.precio_lista) lista_total,
        ep.estado
        from compras c, productos p, estado_pedido ep
        where p.id = c.id_producto
        and p.id_editorial = :idEditorial
        and (c.id_estado = ep.id)
        order by 2,3";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idEditorial' => $idEditorial));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getAllDetalleByEditorial : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getUltimasByProductoConAlias($idProducto){
    try
    {
        $sql = "select
        c.id,
        c.fecha,
        p.titulo producto_nombre,
        c.cantidad,
        c.precio_lista,
        c.precio_costo,
        ep.estado estado_nombre
        from compras c, productos p, estado_pedido ep
        where c.id_producto = p.id
        and c.id_estado = ep.id
        and p.id = :idProducto
        order by c.fecha desc, p.titulo
        limit 5";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idProducto' => $idProducto));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getUltimasByProductoConAlias : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getUltimasByProducto($idProducto){
    try
    {
        $sql = "select
        c.id,
        c.fecha,
        p.titulo producto,
        c.cantidad,
        c.precio_lista,
        c.precio_costo,
        ep.estado estado
        from compras c, productos p, estado_pedido ep
        where c.id_producto = p.id
        and c.id_estado = ep.id
        and p.id = :idProducto
        order by c.fecha desc, p.titulo
        limit 5";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idProducto' => $idProducto));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getUltimasByProducto : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getDetallePorOrdenParaCarrito($idOrdenCompra){
    try
    {
        $sql = "select
        c.id_producto id,
        p.titulo,
        p.sku,
        c.cantidad,
        c.precio_lista,
        c.precio_costo
        from compras c, productos p
        where c.id_producto = p.id and c.id_orden_compra = :idOrdenCompra";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idOrdenCompra' => $idOrdenCompra));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getDetallePorOrdenParaCarrito : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getUltimaFechaAgrupada(){
    try
    {
        $sql = "select max(fecha) fecha from compras group by fecha";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getUltimaFechaAgrupada : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getAllByEstado($idEstado){
    try
    {
        $sql = "select
        c.id,
        c.fecha,
        p.titulo producto,
        c.id_producto,
        c.cantidad,
        c.precio_lista,
        c.precio_costo,
        c.id_orden_compra,
        ep.estado estado
        from compras c, productos p, estado_pedido ep
        where c.id_producto = p.id
        and c.id_estado = ep.id
        and ep.id = :idEstado
        order by c.fecha, c.id_orden_compra, p.titulo";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idEstado' => $idEstado));
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,'getAllByEstado : '.$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getLastByProducto($id){
    try
    {
        $sql = "select * from compras where id_producto = :id order by fecha desc limit 1;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':id' => $id));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }
    
  public function getAllActive(){
    try
    {
        $sql = "select
                oc.id,
                oc.fecha,
                p.nombre proveedor,
                oc.cantidad,
                oc.monto,
                oc.fecha_entrega,
                ep.estado,
                ep.id id_estado,
                pc.cron_entrega
                from orden_compra oc
                join proveedores p on oc.id_proveedor = p.id
                join estado_pedido ep on oc.id_estado_pedido = ep.id
                left join proveedor_calendario pc on pc.proveedor_id = p.id and pc.activo = 1
                where 1 = 1
                and (
                       ( oc.id_estado_pedido = 5 and oc.fecha >= (CURRENT_DATE - INTERVAL 1 MONTH))
                       or (oc.id_estado_pedido !=5)
                )
                order by oc.fecha desc;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        if ($stm->rowCount() > 0) {
          return $this->completarFechaEntregaCalculada($stm->fetchAll(PDO::FETCH_ASSOC));
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getById($id){
    try
    {
        $sql = "select
        oc.id,
        oc.fecha,
        oc.id_proveedor,
        p.nombre proveedor,
        oc.cantidad,
        oc.monto,
        oc.fecha_entrega,
        ep.estado,
        mp.nombre medioPago
        from orden_compra oc, proveedores p, estado_pedido ep, medio_pago mp
        where oc.id_proveedor = p.id
        and oc.id_estado_pedido = ep.id
        and oc.id_medio_pago = mp.id
        and oc.id = :id";

        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':id' => $id));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function getDetails($id){
    try
    {
        $sql = "select
                p.id, 
                p.sku,
                p.titulo,
                c.precio_lista,
                c.precio_costo,
                c.cantidad,
                (c.precio_costo * c.cantidad) costo_total
                from compras c, productos p
                where c.id_orden_compra = :id
                and c.id_producto = p.id
                order by p.titulo
                ;";

        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':id' => $id));
        if ($stm->rowCount() > 0) {
          return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }


  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (id_orden_compra, fecha, id_producto, cantidad, precio_lista, precio_costo, id_estado, id_medio_pago) VALUES (?,?,?,?,?,?,?,?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->idOrdenCompra,
        $this->fecha,
        $this->idProducto,
        $this->cantidad,
        $this->precioLista,
        $this->precioCosto,
        $this->idEstado,
        $this->idMedioPago
        ));
        return $this->pdo->lastInsertId();
    }catch(PDOException $e){
      $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

/*Aquí actualizaremos un registro.*/
  public function update(){
    try{
      $params = array();
      $sql = "fecha = :fecha, id_producto = :idProducto, cantidad = :cantidad, precio_lista = :precioLista, precio_costo = :precioCosto";
      $params[':fecha'] = $this->fecha;
      $params[':idProducto'] = $this->idProducto;
      $params[':cantidad'] = $this->cantidad;
      $params[':precioLista'] = $this->precioLista;
      $params[':precioCosto'] = $this->precioCosto;

      if (!empty($this->idOrdenCompra)){
        $sql .= ", id_orden_compra = :idOrdenCompra";
        $params[':idOrdenCompra'] = $this->idOrdenCompra;
      }

      $sql .= ", id_estado = :idEstado";
      $params[':idEstado'] = $this->idEstado;

      $params[':id'] = $this->id;

      $sqlUpdate = "UPDATE ".self::TABLE." SET ".$sql." WHERE id = :id";
      $stm=$this->pdo->prepare($sqlUpdate);
      $stm->execute($params);
    }catch(PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }


  public function createMasive($id_header, $fecha, $estado, $medioPago, $lista){

    try {
      if (empty($lista)) {
        return;
      }

      $values = array();
      $params = array();
      $this->pdo->beginTransaction();

      foreach ($lista as $item){
        $values[] = '(?,?,?,?,?,?,?,?)';
        $params[] = $id_header;
        $params[] = $fecha;
        $params[] = $item["id"];
        $params[] = $item["cantidad"];
        $params[] = $item["precioLista"];
        $params[] = $item["precioCosto"];
        $params[] = $estado;
        $params[] = $medioPago;
      }
      $sqlInsert = 'INSERT INTO compras (id_orden_compra, fecha, id_producto, cantidad, precio_lista, precio_costo, id_estado, id_medio_pago) values '.implode(',', $values);
      $stm = $this->pdo->prepare($sqlInsert);
      $stm->execute($params);

      $this->pdo->commit();

    }catch(PDOException $e){
      $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
      $this->pdo->rollback();
    }
  }

  public function getCantidadTotalByProducto($idProducto){
    try{
        $sql = "select sum(cantidad) stock from compras where id_producto = :idProducto;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':idProducto' => $idProducto));
        if ($stm->rowCount() > 0) {
          $rs =  $stm->fetch(PDO::FETCH_ASSOC);
          return $rs['stock'];
        } else {
          return 0;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }


  public function getTotalByDate($date){
    try
    {
        $sql = "select sum(cantidad * precio_costo) monto from compras where fecha >= :date and id_medio_pago = 1;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(':date' => $date));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }

    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
}

public function getTotalMonth(){
  try
  {
      $sql = "select 
              sum(precio_costo * cantidad) total_compras,
              count(distinct(id_orden_compra)) cantidad_compras,
              sum(cantidad) total_unidades,
              (sum(precio_costo * cantidad) / sum(cantidad)) promedio,
              (sum(precio_costo) / sum(cantidad)) costo_promedio,
              sum(precio_costo) costo_compras
              from compras
              where
              YEAR(fecha) = YEAR(CURRENT_DATE()) 
              AND MONTH(fecha) = MONTH(CURRENT_DATE())
              ;";
      $stm = $this->pdo->prepare($sql);
      $stm->execute();
      if ($stm->rowCount() > 0) {
        return $stm->fetch(PDO::FETCH_ASSOC);
      } else {
        return null;
      }

  }
  catch (PDOException $e){
      $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
      die($e->getMessage());
  }
}

    public function getAllPendiente(){
    try
    {
        $sql = "select
                oc.id,
                oc.fecha,
                p.nombre proveedor,
                oc.cantidad,
                oc.monto,
                oc.fecha_entrega,
                ep.estado,
                ep.id id_estado,
                pc.cron_entrega
                from orden_compra oc
                join proveedores p on oc.id_proveedor = p.id
                join estado_pedido ep on oc.id_estado_pedido = ep.id
                left join proveedor_calendario pc on pc.proveedor_id = p.id and pc.activo = 1
                where 1 = 1
                and oc.id_estado_pedido !=5
                order by oc.fecha desc, p.nombre;";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        if ($stm->rowCount() > 0) {
          return $this->completarFechaEntregaCalculada($stm->fetchAll(PDO::FETCH_ASSOC));
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
        die($e->getMessage());
    }
  }

  public function actualizarEstado($id, $idEstado){
    try{
      $sql = "update ".self::TABLE." set id_estado = :idEstado WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':idEstado' => $idEstado,
        ':id' => $id,
      ));
    }catch(PDOException $e){
      $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

  public function updateEstadoByConsignacion($idConsignatario, $idEstado){
    try{
      $sql = "update compras set id_estado = :idEstado WHERE id in
    (
        select 
        c.id
        from compras c, productos p
        where c.id_producto = p.id
        and p.id_editorial = :idConsignatario
        and c.id_estado != :idEstadoFiltro
        and c.id in (select con.id_compra from consignaciones con)
    )
    ";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':idEstado' => $idEstado,
        ':idConsignatario' => $idConsignatario,
        ':idEstadoFiltro' => $idEstado
      ));
    }catch(PDOException $e){
      $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

}
