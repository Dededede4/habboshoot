<?php

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
setcookie('conect_choot', 0, 0); // Suffit de supprimer �a & puis c'est tout, il est d�co.
header('Location: http://habboshoot.fr'); // A modifier...
?>
