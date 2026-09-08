<?php
require_once dirname(__DIR__, 2) . '/app/models/users.php';

function actualizarPasswordUsuario($username, $newPassword)
{
    $userPDO = new User();
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    return $userPDO->actualizarPasswordPorUsername($username, $passwordHash);
}
?>
