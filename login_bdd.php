<?php
    session_start();

    require dirname(__FILE__).'/settings.php';
    require dirname(__FILE__).'/includes/php/db_connection_class.php';

    /**
     *Used to check the password
    **/
    function checkPassword($pPseudo, $pPassword)
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        $sql = 'SELECT count(*) as nb_user FROM USER WHERE Pseudo_User="'.$pPseudo.'" AND Pass_User="'.md5(SALT.$pPassword).'";';
        $sth = $lConPDO->query($sql);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $lCon->closeConnection();

        if($row['nb_user'] == 1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     *Used to get the role of the given user
    **/
    function getUserRole($pPseudo)
    {
        $lConUser = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDOUser = $lConUser->openConnection();

        $sqlUser = 'SELECT ROLE.Name_Role as siteRole FROM ROLE, USER WHERE USER.Role_User=ROLE.ID_Role AND USER.Pseudo_User="'.$pPseudo.'";';
        $sthUser = $lConPDOUser->query($sqlUser);
        $rowUser = $sthUser->fetch(PDO::FETCH_ASSOC);
        $lConUser->closeConnection();
        return $rowUser['siteRole'];
    }

    if(isset($_GET['zt_uname']) && isset($_GET['zt_upass']))
    {
        $name = $_GET['zt_uname'];
        $pass = $_GET['zt_upass'];

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $page = LIST_PAGE;

        if(checkPassword($name, $pass))
        {
            session_regenerate_id();
            $_SESSION['USER'] = $name;
            $_SESSION['ROLE'] = getUserRole($name);

            header("Location: http://$host$uri/$page");
            exit;
        }
        else
        {
            //Wrong password
            session_destroy();
            header("Location: http://$host$uri/$page");
            exit;
        }
    }
?>