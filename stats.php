<?php
    session_start();
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="includes/css/framalang.css"/>
    </head>
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
    <div id="header">
        <h1>Traductions du groupe Framalang</h1>
    </div>

    <div id="login">
        <?php
            if(!isset($_SESSION['USER']))
            {
                echo '<form action="login_bdd.php" method="get" >';
                echo 'Login <input type="text" name="zt_uname" maxlength="50"><br /><br />';
                echo 'Mot de passe <input type="password" name="zt_upass" size="16">';
                echo '<input type="submit" value="Login" />';
                echo '</form>';
            }
            else
            {
                echo '<p>Identifié en tant que '.$_SESSION['USER'].' (<a href="logout.php">Déconnexion</a>).</p>';
            }
        ?>
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
    <div id="navigator">
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
            echo "<a href='".LIST_PAGE."?".$myPath."page=0'>Première page</a> - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage-1)."'>Page précédente</a> - ";
        }
        echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage)."'>Page courante</a> ";
        if($idpage!=$pageCount)
        {
            echo " - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage+1)."'>Page suivante</a> - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".$pageCount."'>Dernière page</a>";
        }
    ?>
    </div>
    <div id="content">
        <br /><br />
        <table>
        <thead>
            <tr class="titleline">
                <th><?php echo Utils::generateLinkWithPage('Traduction', 'Title_Trad', $idpage) ?></th>
                <th><?php echo Utils::generateLinkWithPage('Statut', 'Name_Status', $idpage) ?></th>
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
        <?php $lineIndicator=0 ?>
        <?php while($row = $sth->fetch(PDO::FETCH_ASSOC)): ?>
            <?php if ($lineIndicator==0): ?>
                <?php
                    if($row[ID_Priority]==0)
                    {
                        echo '<tr class="altline1">';
                    }
                    else
                    {
                        echo '<tr class="altlineprio1">';
                    }
                    $lineIndicator=1;
                ?>
            <?php else:?>
                <?php
                    if($row[ID_Priority]==0)
                    {
                        echo '<tr class="altline2">';
                    }
                    else
                    {
                        echo '<tr class="altlineprio2">';
                    }
                    $lineIndicator=0;
                ?>
            <?php endif;?>
            <td>
                <?php
                    if(strlen($row[UrlPub_Trad])>0 && $row[ID_Status]==5)
                    {
                        echo '<a target="_blank" href="'.$row[UrlPub_Trad].'">'.$row[Title_Trad].'</a> (<a target="_blank" href="'.$row[UrlPad_Trad].'">Pad</a>)';
                    }
                    else
                    {
                        echo '<a target="_blank" href="'.$row[UrlPad_Trad].'">'.$row[Title_Trad].'</a>';
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
                    echo "<progress value='".$row[Percent_Status]."' max='6' title='".$nothtml5."'>[".$nothtml5."]</progress> ".utf8_encode($tradstatus);
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
                            echo "<a href='userspace/edit_trad.php?ID_Trad=".$row[ID_Trad]."'>Modifier la traduction</a>";
                            if(strcasecmp($_SESSION['ROLE'], "Administrateur") == 0)
                            {
								echo " - ";
                                echo "<a href='userspace/del_trad.php?ID_Trad=".$row[ID_Trad]."'>Supprimer la traduction</a>";
                            }
                            echo '</form>';
                        }
                        else
                        {
                            echo "<a href='userspace/edit_trad.php?ID_Trad=".$row[ID_Trad]."'>Modifier la traduction</a>";
                            if(strcasecmp($_SESSION['ROLE'], "Administrateur") == 0)
                            {
                                echo " - ";
                                echo "<a href='userspace/del_trad.php?ID_Trad=".$row[ID_Trad]."'>Supprimer la traduction</a>";
                            }
                        }
                        echo "</td>";
                    }
                ?>
            </tr>
        <?php EndWhile; ?>
        </tbody>
        </table>
        <br />
    </div>
    <div id="navigator">
    <?php
        if($idpage!=0)
        {
            echo "<a href='".LIST_PAGE."?".$myPath."page=0'>Première page</a> - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage-1)."'>Page précédente</a> - ";
        }
        echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage)."'>Page courante</a> ";
        if($idpage!=$pageCount)
        {
            echo " - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".($idpage+1)."'>Page suivante</a> - ";
            echo "<a href='".LIST_PAGE."?".$myPath."page=".$pageCount."'>Dernière page</a>";
        }
        echo "<p>".($idpage+1)."/".($pageCount+1)."</p>";
    ?>
    </div>
    <div id="footer">
        <hr />
        <p>Réalisé par Céline Libéral pour le Framalang. (2013)</p>
    </div>
</html>