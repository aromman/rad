<?php
require_once dirname(__DIR__, 2) . '/app/models/roles.php';

function agregarRolesRapido($roles)
{
    foreach ($roles as $rol) {
        $rolPDO = new Rol();
        $rolPDO->rol = $rol;
        $rolPDO->create();
    }
}
?>
