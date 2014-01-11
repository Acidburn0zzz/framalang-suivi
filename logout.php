<?php
    session_start();
    session_destroy();
    
    require_once dirname(__FILE__).'/settings.php';

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $page = LIST_PAGE;

    header("Location: http://$host$uri/$page");
    exit;
?>