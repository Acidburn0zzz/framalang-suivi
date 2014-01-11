<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';

    if(isset($_SESSION['USER']) && isset($_GET['zl_etat']) && isset($_GET['h_id']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        if($_GET['zl_etat'] == 3)
        {
            $sql = "UPDATE TRADUCTION SET ID_Status=".$_GET['zl_etat'].", DateEnd_Trad=".date("Y-m-d")." WHERE ID_Trad=".$_GET['h_id'].";";
        }
        else
        {
            $sql = "UPDATE TRADUCTION SET ID_Status=".$_GET['zl_etat']." WHERE ID_Trad=".$_GET['h_id'].";";
        }

        $sth = $lConPDO->exec($sql);

        $lCon->closeConnection();
    }

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $page = '../'.LIST_PAGE;

    header("Location: http://$host$uri/$page");
    exit;
?>