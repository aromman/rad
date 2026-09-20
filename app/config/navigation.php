<?php

/**
 * Página inicial de cada rol después de ingresar al sistema.
 */
function navigation_default_pages()
{
    return array(
        0 => '/turno/turno.php',
        1 => '/turno/turno.php',
        2 => '/informes/ventas-canales-mensual.php',
        3 => '/dashboard.php',
    );
}

function navigation_default_path($role)
{
    $defaultPages = navigation_default_pages();
    $role = (int) $role;

    return isset($defaultPages[$role]) ? $defaultPages[$role] : '/bienvenido.php';
}

/**
 * Construye la navegación disponible para un usuario.
 *
 * Cada sección contiene grupos y cada grupo contiene sus enlaces. La
 * autorización de las páginas debe seguir validándose en cada endpoint.
 */
function build_navigation($role, $channelType)
{
    $sections = array(
        'operaciones' => array('label' => 'Operaciones', 'icon' => 'fa-cash-register', 'groups' => array()),
        'ventas' => array('label' => 'Ventas', 'icon' => 'fa-cart-shopping', 'groups' => array()),
        'productos' => array('label' => 'Productos', 'icon' => 'fa-boxes-stacked', 'groups' => array()),
        'compras' => array('label' => 'Compras', 'icon' => 'fa-truck', 'groups' => array()),
        'finanzas' => array('label' => 'Finanzas', 'icon' => 'fa-wallet', 'groups' => array()),
        'personal' => array('label' => 'Personal', 'icon' => 'fa-users', 'groups' => array()),
        'administracion' => array('label' => 'Administración', 'icon' => 'fa-gear', 'groups' => array()),
        'informes' => array('label' => 'Informes', 'icon' => 'fa-chart-column', 'groups' => array()),
    );

    $addGroup = function ($section, $label, $items) use (&$sections) {
        $sections[$section]['groups'][] = array('label' => $label, 'items' => $items);
    };

    if ($role == 0 || $role == 1) {
        if ($channelType === 'CAFETERIA') {
            $addGroup('operaciones', '', array(
                array('label' => 'Gestión de Caja', 'path' => '/turno/turno.php', 'icon' => 'fa-cash-register'),
                array('label' => 'Nueva Venta', 'path' => '/pos/pos.php', 'icon' => 'fa-circle-plus'),
            ));
            $addGroup('productos', 'Productos', array(
                array('label' => 'Productos', 'path' => '/productos/productos.php', 'icon' => 'fa-box'),
            ));
        } else {
            $addGroup('ventas', '', array(
                array('label' => 'Ventas', 'path' => '/pos/ventas.php', 'icon' => 'fa-receipt'),
            ));
            $addGroup('operaciones', '', array(
                array('label' => 'Gestión de Caja', 'path' => '/turno/turno.php', 'icon' => 'fa-cash-register'),
                array('label' => 'Nueva Venta', 'path' => '/pos/pos.php', 'icon' => 'fa-circle-plus'),
                array('label' => 'Pedidos', 'path' => '/pedidos/pedidos.php', 'icon' => 'fa-clipboard-list'),
            ));
            $addGroup('productos', 'Productos', array(
                array('label' => 'Productos', 'path' => '/productos/productos.php', 'icon' => 'fa-box'),
                array('label' => 'Análisis de Precios', 'path' => '/precios/analisis-precios.php', 'icon' => 'fa-tags'),
                array('label' => 'Más Vendidos', 'path' => '/productos-mas-vendidos.php', 'icon' => 'fa-ranking-star'),
            ));
            $addGroup('productos', 'Inventario', array(
                array('label' => 'Control', 'path' => '/inventario/control.php', 'icon' => 'fa-clipboard-check'),
                array('label' => 'Agotados', 'path' => '/inventario/agotados.php', 'icon' => 'fa-triangle-exclamation'),
            ));
            $addGroup('productos', 'Ofertas', array(
                array('label' => 'Ofertas Activas', 'path' => '/ofertas/activas.php', 'icon' => 'fa-tag'),
            ));
        }
    }

    if ($role == 0) {
        $addGroup('administracion', 'Paneles', array(
            array('label' => 'Panel de Control', 'path' => '/dashboard.php', 'icon' => 'fa-gauge-high'),
            array('label' => 'Mes Actual', 'path' => '/pos/dashboard.php', 'icon' => 'fa-chart-line'),
            array('label' => 'Actualizar Objetivos', 'path' => '/actualizar-objetivos.php', 'icon' => 'fa-bullseye'),
        ));
        $addGroup('finanzas', 'Rentabilidad', array(
            array('label' => 'Dashboard financiero', 'path' => '/rentabilidad/dashboard-financiero.php', 'icon' => 'fa-chart-pie'),
            array('label' => 'Rentabilidad', 'path' => '/rentabilidad/rentabilidad.php', 'icon' => 'fa-arrow-trend-up'),
        ));
        $addGroup('finanzas', 'Tableros', array(
            array('label' => 'Ticket promedio', 'path' => '/finanzas/ticket-promedio.php', 'icon' => 'fa-receipt'),
            array('label' => 'Estado de resultado', 'path' => '/finanzas/estado-de-resultado.php', 'icon' => 'fa-file-invoice-dollar'),
            array('label' => 'Sistema 4C', 'path' => '/finanzas/sistema-4c.php', 'icon' => 'fa-layer-group'),
        ));
        $addGroup('finanzas', 'Flujo de Caja', array(
            array('label' => 'Flujo de Caja 12 Meses', 'path' => '/cashflow/flujo-caja-12m.php', 'icon' => 'fa-chart-line'),
            array('label' => 'Flujo Proyectado', 'path' => '/cashflow/flujo-caja-proy.php', 'icon' => 'fa-chart-area'),
        ));
        $addGroup('finanzas', 'Presupuesto', array(
            array('label' => 'Tablero', 'path' => '/presupuesto/dashboard.php', 'icon' => 'fa-gauge'),
            array('label' => 'Presupuestos', 'path' => '/presupuesto/presupuestos.php', 'icon' => 'fa-file-invoice-dollar'),
        ));
        $addGroup('ventas', '', array(
            array('label' => 'Detalle de Ventas', 'path' => '/ventas/ventas.php', 'icon' => 'fa-receipt'),
            array('label' => 'Ventas a Facturar', 'path' => '/pos/ventas-a-facturar.php', 'icon' => 'fa-file-invoice'),
        ));
        $addGroup('finanzas', 'Gastos', array(
            array('label' => 'Gastos', 'path' => '/gastos/gastos.php', 'icon' => 'fa-money-bill-transfer'),
            array('label' => 'Histórico de Gastos', 'path' => '/gastos/historial.php', 'icon' => 'fa-clock-rotate-left'),
        ));
        $addGroup('ventas', '', array(
            array('label' => 'Clientes', 'path' => '/clientes/clientes.php', 'icon' => 'fa-address-book'),
        ));
        $existenciasItem = array('label' => 'Existencias', 'path' => '/stock/existencias.php', 'icon' => 'fa-warehouse');
        $costoMantenimientoItem = array('label' => 'Costo de Mantenimiento de Inventario', 'path' => '/stock/costo-mantenimiento.php', 'icon' => 'fa-money-bill-transfer');
        $inventarioUnificado = false;
        foreach ($sections['productos']['groups'] as &$grupoProductos) {
            if ($grupoProductos['label'] === 'Inventario') {
                $grupoProductos['items'][] = $existenciasItem;
                $grupoProductos['items'][] = $costoMantenimientoItem;
                $inventarioUnificado = true;
                break;
            }
        }
        unset($grupoProductos);

        if (!$inventarioUnificado) {
            $addGroup('productos', 'Inventario', array($existenciasItem, $costoMantenimientoItem));
        }
        $addGroup('compras', 'Compras', array(
            array('label' => 'Calendario', 'path' => '/proveedores/calendario.php', 'icon' => 'fa-calendar-days'),
            array('label' => 'Proveedores', 'path' => '/proveedores/proveedores.php', 'icon' => 'fa-truck-field'),
            array('label' => 'Compras', 'path' => '/compras/compras.php', 'icon' => 'fa-cart-flatbed'),
            array('label' => 'Nueva Compra', 'path' => '/compras/buy.php', 'icon' => 'fa-circle-plus'),
        ));
        $addGroup('finanzas', 'Caja', array(
            array('label' => 'Movimientos de Caja', 'path' => '/turno/movimientos.php', 'icon' => 'fa-money-bill-transfer'),
        ));
        $addGroup('finanzas', 'Cuentas', array(
            array('label' => 'Cuentas Comerciales', 'path' => '/cuentas/cuentas.php', 'icon' => 'fa-building-columns'),
            array('label' => 'Cuentas Personales', 'path' => '/cuentas/cuentas-personales.php', 'icon' => 'fa-wallet'),
        ));
        $addGroup('productos', 'Catálogo', array(
            array('label' => 'Marcas', 'path' => '/editoriales/editoriales.php', 'icon' => 'fa-building'),
            array('label' => 'Formatos', 'path' => '/maestros/productos-formato.php', 'icon' => 'fa-shapes'),
            array('label' => 'Series', 'path' => '/maestros/productos-serie.php', 'icon' => 'fa-layer-group'),
        ));
        $posiblesOfertasItem = array('label' => 'Posibles Ofertas', 'path' => '/ofertas/ofertas.php', 'icon' => 'fa-percent');
        $ofertasVendidasItem = array('label' => 'Ofertas Vendidas', 'path' => '/ofertas/vendidas.php', 'icon' => 'fa-tags');
        $ofertasUnificado = false;
        foreach ($sections['productos']['groups'] as &$grupoOfertas) {
            if ($grupoOfertas['label'] === 'Ofertas') {
                $grupoOfertas['items'] = array_merge(array($posiblesOfertasItem), $grupoOfertas['items'], array($ofertasVendidasItem));
                $ofertasUnificado = true;
                break;
            }
        }
        unset($grupoOfertas);

        if (!$ofertasUnificado) {
            $addGroup('productos', 'Ofertas', array(
                $posiblesOfertasItem,
                array('label' => 'Ofertas Activas', 'path' => '/ofertas/activas.php', 'icon' => 'fa-tag'),
                $ofertasVendidasItem,
            ));
        }
        $addGroup('administracion', 'Locaciones', array(
            array('label' => 'Canales de Venta', 'path' => '/canales.php', 'icon' => 'fa-store'),
        ));
        $addGroup('personal', 'Recursos Humanos', array(
            array('label' => 'Panel de RRHH', 'path' => '/empleados/dashboard.php', 'icon' => 'fa-gauge-high'),
            array('label' => 'Empleados', 'path' => '/empleados/empleados.php', 'icon' => 'fa-id-badge'),
            array('label' => 'Pagos', 'path' => '/empleados/pagos.php', 'icon' => 'fa-money-check-dollar'),
            array('label' => 'Salarios', 'path' => '/maestros/salarios.php', 'icon' => 'fa-sack-dollar'),
            array('label' => 'Equipos', 'path' => '/maestros/equipos.php', 'icon' => 'fa-people-group'),
            array('label' => 'Integrantes de Equipo', 'path' => '/maestros/equipo-empleado.php', 'icon' => 'fa-user-group'),
        ));
        $addGroup('personal', 'Cuentas', array(
            array('label' => 'Cuentas', 'path' => '/cuentas/cuentas-empleados.php', 'icon' => 'fa-building-columns'),
        ));
        $addGroup('administracion', 'Tablas Maestras', array(
            array('label' => 'Clientes', 'path' => '/clientes.php', 'icon' => 'fa-address-book'),
            array('label' => 'Órdenes de Compra', 'path' => '/orden-compra.php', 'icon' => 'fa-file-signature'),
            array('label' => 'Compras', 'path' => '/compras.php', 'icon' => 'fa-cart-flatbed'),
            array('label' => 'Medios de Pago', 'path' => '/maestros/medios-pago.php', 'icon' => 'fa-credit-card'),
            array('label' => 'Descuentos', 'path' => '/maestros/descuentos.php', 'icon' => 'fa-percent'),
            array('label' => 'Clases de Gastos', 'path' => '/maestros/gastos-clase.php', 'icon' => 'fa-tags'),
        ));
        $addGroup('informes', 'Informes', navigation_report_items());
        $addGroup('administracion', 'Usuarios', array(
            array('label' => 'Usuarios', 'path' => '/usuarios/usuarios.php', 'icon' => 'fa-users-gear'),
            array('label' => 'Roles', 'path' => '/usuarios/roles.php', 'icon' => 'fa-user-shield'),
        ));
    }

    if ($role == 2) {
        $addGroup('informes', 'Informes', array_slice(navigation_report_items(), 1));
    }

    return array_filter($sections, function ($section) {
        return !empty($section['groups']);
    });
}

function navigation_report_items()
{
    return array(
        array('label' => 'Valoración ABC', 'path' => '/informes/valoracion-abc.php', 'icon' => 'fa-chart-column'),
        array('label' => 'Ventas por Canal Mensual', 'path' => '/informes/ventas-canales-mensual.php', 'icon' => 'fa-chart-line'),
        array('label' => 'Compras por Canal Mensual', 'path' => '/informes/compras-canales-mensual.php', 'icon' => 'fa-chart-area'),
        array('label' => 'Gastos por Canal Mensual', 'path' => '/informes/gastos-canales-mensual.php', 'icon' => 'fa-chart-pie'),
    );
}
