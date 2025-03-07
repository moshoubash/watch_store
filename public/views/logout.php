<?php
session_start(); 
session_unset(); 
session_destroy(); 


header("Location: http://localhost/watch_store_clone/public/");
exit();
?>
