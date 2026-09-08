<?php
require_once dirname(__DIR__, 2) . '/app/models/users.php';

function existeUsuario($username)
{
    $userPDO = new User();
    return $userPDO->getByUserName($username) !== null;
}

function registrarUsuarioInvitado($username, $password, $email)
{
    $userPDO = new User();
    $userPDO->usuario = $username;
    $userPDO->email = $email;
    $userPDO->idRol = 99; // Invitado
    $userPDO->password = password_hash($password, PASSWORD_DEFAULT);

    return $userPDO->create();
}
?>
