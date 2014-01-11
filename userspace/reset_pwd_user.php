<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';

    if(isset($_SESSION['USER']) && isset($_GET['ID_Trad']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        $sql = "DELETE FROM TRADUCTION WHERE ID_Trad=".$_GET['ID_Trad'].";";

        $sth = $lConPDO->exec($sql);

        $lCon->closeConnection();
    }

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $page = '../'.LIST_PAGE;

    header("Location: http://$host$uri/$page");
    exit;
?>