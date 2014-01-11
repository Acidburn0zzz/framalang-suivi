<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';

    if(isset($_SESSION['USER']) && isset($_GET['zt_pseudo']) && isset($_GET['zt_pass']) && isset($_GET['zt_mail']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        if(isset($_GET['chk_admin']))
        {
            $sql = "INSERT INTO `USER` (`Pseudo_User`, `Pass_User`, `Role_User`) VALUES ('".$_GET['zt_pseudo']."', '".md5(SALT.$_GET['zt_pass'])."', 0);";
        }
        else
        {
            $sql = "INSERT INTO `USER` (`Pseudo_User`, `Pass_User`, `Role_User`) VALUES ('".$_GET['zt_pseudo']."', '".md5(SALT.$_GET['zt_pass'])."', 1);";
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