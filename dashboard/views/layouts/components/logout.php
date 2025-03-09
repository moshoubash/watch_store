<?php
    session_start(); 
    session_unset(); 
    session_destroy(); 


    header("Location: /watch_store/public");
    exit();
?>
