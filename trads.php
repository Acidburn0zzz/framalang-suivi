<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Suivi Framalang</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="includes/css/sticky-footer.css"/>
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="includes/fwk/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="includes/css/framalang.css"/>
    </head>
    <body>
    <?php
        require_once dirname(__FILE__).'/settings.php';
        require_once dirname(__FILE__).'/includes/php/db_connection_class.php';
        require_once dirname(__FILE__).'/includes/php/utils.php';

        if(isset($_GET['page']))
        {
            $idpage=$_GET['page'];
        }
        else
        {
            $idpage=0;
        }

        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        $authorizedsort=array('Title_Trad', 'Name_Status', 'DateCre_Trad');
        $order_by=in_array($_GET['order'],$authorizedsort) ? $_GET['order'] : 'Title_Trad';

        $order_dir = isset($_GET['inverse']) ? 'DESC' : 'ASC';

        $sql = 'SELECT ID_Trad, Title_Trad, UrlPad_Trad, UrlPub_Trad, ID_Priority, DateCre_Trad, T.ID_Status, Name_Status, Percent_Status FROM TRADUCTION T, STATUS S WHERE S.ID_Status = T.ID_Status ORDER BY '.$order_by.' '.$order_dir.' LIMIT '.($idpage*PAGE_SIZE).','.PAGE_SIZE.';';
        $sth = $lConPDO->query($sql);
    ?>
    <?php
        $lCon->closeConnection();
    ?>
     <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
         <div class="navbar-header">
            <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse" type="button"></button>
            <a class="navbar-brand">Traductions du groupe Framalang</a>
         </div>
         <div class="navbar-collapse collapse">
         <?php
             if(!isset($_SESSION['USER']))
             {
                echo '<form class="navbar-form navbar-right" action="login_bdd.php" method="get" >';
                echo '<div class="form-group">';
                echo '<input class="form-control" type="text" name="zt_uname" maxlength="50" placeholder="Utilisateur">';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<input class="form-control" type="password" name="zt_upass" placeholder="Mot de passe">';
                echo '</div>';
                echo '<button class="btn btn-success" type="submit">Login</button>';
                echo '</form>';
             }
             else
             {
                echo '<ul class="nav navbar-brand navbar-right">';
                echo '<li>Bonjour, '.$_SESSION['USER'].' (<a href="logout.php">Déconnexion</a>).</li>';
                echo '</ul>';
             }
         ?>
         </div>
    </div>
                 
    <div id="ajout_utilisateur">
        <?php
            if(isset($_SESSION['USER']) && strcasecmp($_SESSION['ROLE'], "Administrateur") == 0)
            {
                echo '<hr />';
                echo "<h4>Ajout d'un utilisateur</h4>";
                echo '<form action="userspace/add_user.php" method="get" >';
                echo 'Pseudo <input type="text" name="zt_pseudo" maxlength="50"> ';
                echo 'Mot de passe <input type="text" name="zt_pass" maxlength="256" size="16"> ';
                echo 'Adresse mail <input type="text" name="zt_mail" maxlength="150" size="20"> ';
                echo '<input type="checkbox" name="chk_admin" value="admin"> Administrateur ? ';
                echo '<input type="submit" value="Ajouter un utilisateur" />';
                echo '</form>';
            }
        ?>
    </div>
    <div id="ajout_traduction">
        <?php
            if(isset($_SESSION['USER']))
            {
                echo '<hr />';
                echo "<h4>Ajout d'une traduction</h4>";
                echo '<form action="userspace/add_trad.php" method="get" >';
                echo 'Titre <input type="text" name="zt_trad_name" maxlength="127" size="32"> ';
                echo 'Url du pad (avec http(s)://) <input type="text" name="zt_trad_padurl" size="64"> ';
                echo '<input type="checkbox" name="chk_trad_prio" value="prio"> Prioritaire ? ';
                echo '<input type="submit" value="Ajouter une traduction" />';
                echo '</form>';
                echo '<hr />';
            }
        ?>
    </div>
    <div id="wrap">
    <div id="content">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?php echo Utils::generateLinkWithPage('Traduction', 'Title_Trad', $idpage) ?></th>
                <th><?php echo Utils::generateLinkWithPage('Avancement', 'Name_Status', $idpage) ?></th>
                <th><?php echo Utils::generateLinkWithPage('Date de création', 'DateCre_Trad', $idpage) ?></th>
                <?php
                    if(isset($_SESSION['USER']))
                    {
                        echo "<th>Opérations</th>";
                    }
                ?>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $sth->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td>
                <?php
                    if(strlen($row[UrlPub_Trad])>0 && $row[ID_Status]==3)
                    {
                        echo '<a target="_blank" href="'.$row[UrlPub_Trad].'">'.$row[Title_Trad].'</a> (<a target="_blank" href="'.$row[UrlPad_Trad].'">Pad</a>) <span class="label label-success">Publiée</span>';
                    }
                    else
                    {
                        if($row[ID_Status]==1)
                        {
                            echo '<a target="_blank" href="'.$row[UrlPad_Trad].'">'.$row[Title_Trad].'</a>  <span class="label label-info">En relecture</span> ';
                        }
                        elseif($row[ID_Status]==2)
                        {
                            echo '<a target="_blank" href="'.$row[UrlPad_Trad].'">'.$row[Title_Trad].'</a>  <span class="label label-warning">En attente de publication</span> ';
                        }
                        else
                        {
                            echo '<a target="_blank" href="'.$row[UrlPad_Trad].'">'.$row[Title_Trad].'</a> ';
                        }
                    }
                    if($row[ID_Priority]!=0)
                    {
                        echo ' <span class="label label-danger">Prioritaire</span>';
                    }
                ?>
            </td>
            <td>
                <?php
                    if ($row[DateEnd_Trad]!=null)
                    {
                        $date=$row[DateEnd_Trad];
                    }
                    elseif($row[DateDeb_Trad]!=null)
                    {
                        $date=$row[DateDeb_Trad];
                    }
                    else
                    {
                        $date=$row[DateCre_Trad];
                    }

                    $tradstatus=$row[Name_Status];
                    $actualencoding=mb_detect_encoding($tradstatus);
                    if($actualencoding != "UTF-8")
                    {
                        $tradstatus=iconv($actualencoding, "UTF-8", $tradstatus);
                    }

                    $nothtml5 = utf8_encode($tradstatus)." depuis le ".strftime('%d/%m/%Y', strtotime($date));
                    echo "<progress value='".$row[Percent_Status]."' max='4' title='".$nothtml5."'>[".$nothtml5."]</progress> ";
                ?>
            </td>
            <td>
                <?php
                    echo strftime('%d/%m/%Y', strtotime($row[DateCre_Trad]));
                ?>
            </td>
                <?php
                    if(isset($_SESSION['USER']))
                    {
                        echo "<td>";
                        if(Utils::getCountOfPossibleStates($row[ID_Status]) > 0)
                        {
                            $possibleStates = Utils::getPossibleStates($row[ID_Status]);
                            echo '<form action="userspace/update_state.php" method="get" >';
                            echo '<input type="hidden" name="h_id" value="'.$row[ID_Trad].'" />';
                            echo '<SELECT name="zl_etat">';
                        ?>
                            <?php while($possibleState = $possibleStates->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php
                                if(($row[ID_Status]+1) == $possibleState[ID_Status])
                                {
                                    echo '<option selected="selected" value="'.$possibleState[ID_Status].'">'.utf8_encode($possibleState[Name_Status]).'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$possibleState[ID_Status].'">'.utf8_encode($possibleState[Name_Status]).'</option>';
                                }
                            ?>
                            <?php EndWhile; ?>
                        <?php
                            echo '</SELECT>';
                            echo '<input type="submit" value="OK" />';
                            echo " - ";
                            echo " <a href='userspace/edit_trad.php?ID_Trad=".$row[ID_Trad]."'><span class='glyphicon glyphicon-pencil'></span></a>";
                            if(strcasecmp($_SESSION['ROLE'], "Administrateur") == 0)
                            {
                                //echo '<button type="button" class="btn btn-default btn">';
                                echo " <a href='userspace/del_trad.php?ID_Trad=".$row[ID_Trad]."'><span class='glyphicon glyphicon-remove'></span></a>";
                                //echo '</button>';
                            }
                            echo '</form>';
                        }
                        else
                        {
                            echo " <a href='userspace/edit_trad.php?ID_Trad=".$row[ID_Trad]."'><span class='glyphicon glyphicon-pencil'></span></a>";
                            if(strcasecmp($_SESSION['ROLE'], "Administrateur") == 0)
                            {
                                //echo '<button type="button" class="btn btn-default">';
                                echo " <a href='userspace/del_trad.php?ID_Trad=".$row[ID_Trad]."'><span class='glyphicon glyphicon-remove'></span></a>";
                                //echo '</button>';
                            }
                        }
                        echo "</td>";
                    }
                ?>
            </tr>
        <?php EndWhile; ?>
        </tbody>
        </table>
    </div>
    <div class="container">
        <ul class="pagination">
        <?php
            $tradCount = Utils::getNbTrads();
            $pageCount = (int) ($tradCount/PAGE_SIZE);
            
            $queryString=$_SERVER['QUERY_STRING'];
            $pathWithoutPage=explode('page=', $queryString);
            if(isset($pathWithoutPage))
            {
                $myPath=$pathWithoutPage[0];
            }
            else
            {
                $myPath=$_SERVER['QUERY_STRING'];
            }
            
            if($idpage!=0)
            {
                echo "<li><a href='".LIST_PAGE."?".$myPath."page=0'>&laquo;</a></li>";
                echo "<li><a href='".LIST_PAGE."?".$myPath."page=".($idpage-1)."'>".$idpage."</a></li>";
            }
            echo "<li class='active'><a href='".LIST_PAGE."?".$myPath."page=".($idpage)."'>".($idpage+1)."</a></li>";
            if($idpage!=$pageCount)
            {
                for($pageNum=($idpage+1); $pageNum<($pageCount+1); $pageNum++)
                {
                    echo "<li><a href='".LIST_PAGE."?".$myPath."page=".($pageNum)."'>".($pageNum+1)."</a></li>";
                }
                echo "<li><a href='".LIST_PAGE."?".$myPath."page=".$pageCount."'>&raquo;</a></li>";
            }
            ?>
        </ul>
    </div>
</div>

    <div id="footer">
        <div class="container">
                 <p class="text-muted credit">Réalisé par Céline Libéral pour le Framalang. (2013).</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src=".includes/fwk/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>