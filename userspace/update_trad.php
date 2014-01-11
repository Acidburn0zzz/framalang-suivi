<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';
    
    if(isset($_SESSION['USER']) && isset($_GET['zt_trad_name']) && isset($_GET['zt_trad_padurl']) && isset($_GET['zt_trad_puburl']) && isset($_GET['h_id']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        $sqlString = "UPDATE TRADUCTION SET Title_Trad='".$_GET['zt_trad_name']."', UrlPad_Trad='".$_GET['zt_trad_padurl']."', UrlPub_Trad='".$_GET['zt_trad_puburl']."', ";
        
        if(isset($_GET['chk_trad_prio']))
        {
            $sqlString = $sqlString."ID_Priority='1'";
        }
        else
        {
            $sqlString = $sqlString."ID_Priority='0'";
        }
        
        if(isset($_GET['zl_etat']))
        {
            if($_GET['zl_etat'] == 3)
            {
                $sqlString = $sqlString.", ID_Status='".$_GET['zl_etat']."', DateEnd_Trad='".date("Y-m-d")."'";
            }
            else
            {
                $sqlString = $sqlString.", ID_Status='".$_GET['zl_etat']."'";
            }
        }

        $sqlString = $sqlString." WHERE ID_Trad='".$_GET['h_id']."';";
        
        $sth = $lConPDO->exec($sqlString);

        $lCon->closeConnection();
    }

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $page = '../'.LIST_PAGE;

    header("Location: http://$host$uri/$page");
    exit;
?>