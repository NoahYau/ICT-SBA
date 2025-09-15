<?php
    session_start(); // we want to use sessions
    session_unset(); // we want to remove $_SESSION for the current visit
    session_destroy(); // we want to destroy $_SESSION, new cookie given to client
    header("location: products.php");
?>