<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $page = '../'.LIST_PAGE;
    $url = "http://".$host.$uri."/".$page;
    
    
    if(isset($_SESSION['USER']) && isset($_GET['zt_trad_name']) && isset($_GET['zt_trad_padurl']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        if(isset($_GET['chk_trad_prio']))
        {
            $sql = "INSERT INTO `TRADUCTION` (`Title_Trad`, `UrlPad_Trad`, `DateCre_Trad`, `ID_Status`, `ID_Priority`) VALUES ('".$_GET['zt_trad_name']."', '".$_GET['zt_trad_padurl']."', '".date("Y-m-d")."', 0, 1);";
        }
        else
        {
            $sql = "INSERT INTO `TRADUCTION` (`Title_Trad`, `UrlPad_Trad`, `DateCre_Trad`, `ID_Status`, `ID_Priority`) VALUES ('".$_GET['zt_trad_name']."', '".$_GET['zt_trad_padurl']."', '".date("Y-m-d")."', 0, 0);";
        }

        $sth = $lConPDO->exec($sql);

        $lCon->closeConnection();
    }

    header("Location: $url");
    exit;
?>