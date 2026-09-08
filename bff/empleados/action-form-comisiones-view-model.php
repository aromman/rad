<?php
require_once dirname(__DIR__, 2) . '/app/models/empleados.php';
require_once dirname(__DIR__, 2) . '/app/models/comision.php';
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';

function obtenerEmpleadosSelectorViewModel()
{
    $empleadoPDO = new Empleado();

    return array(
        'empleados' => (array) $empleadoPDO->getAllOrderByApellidoNombre(),
    );
}

function agregarComisionesRapido($fechas, $detalles, $montos, $empleados)
{
    foreach ($detalles as $key => $detalle) {
        // importe en comision pasar a negativo
        $importe = $montos[$key] > 0 ? $montos[$key] * -1 : $montos[$key];

        $comisionPDO = new Comision();
        $comisionPDO->fecha = $fechas[$key];
        $comisionPDO->detalle = $detalle;
        $comisionPDO->monto = $importe;
        $comisionPDO->idEmpleado = $empleados[$key];
        $comisionPDO->idLiquidacion = null;
        $comisionPDO->create();

        // registro gasto
        $importe = $importe < 0 ? $importe * -1 : $importe;

        $gastosPDO = new Gastos();
        $gastosPDO->fecha = $fechas[$key];
        $gastosPDO->detalle = $detalle;
        $gastosPDO->monto = $importe;
        $gastosPDO->tipo = 'C';
        $gastosPDO->create();
    }
}
?>
