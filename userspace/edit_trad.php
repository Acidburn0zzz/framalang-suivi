<?php
    session_start();

    require_once dirname(__FILE__).'/../settings.php';
    require_once dirname(__FILE__).'/../includes/php/utils.php';
    require_once dirname(__FILE__).'/../includes/php/db_connection_class.php';

    if(isset($_SESSION['USER']) && isset($_GET['ID_Trad']))
    {
        $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $lConPDO = $lCon->openConnection();

        $sql = "SELECT * FROM TRADUCTION WHERE ID_Trad=".$_GET['ID_Trad'].";";

        $sth = $lConPDO->query($sql);
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		echo "<html>";
    	echo "<head>";
        echo "<meta http-equiv='content-type' content='text/html; charset=utf-8' />";
        echo "<link rel='stylesheet' type='text/css' href='../includes/css/framalang.css'/>";
		echo "</head>";
		echo "<body>";
		
		echo "<h2>Edition des informations de la traduction '".$row['Title_Trad']."'</h2>";
		echo "<hr />";
				
        echo '<form action="./update_trad.php" method="get" >';
        echo '<input type="hidden" name="h_id" value="'.$_GET['ID_Trad'].'" />';
        echo 'Titre <input type="text" name="zt_trad_name" maxlength="127" size="32" value="'.$row['Title_Trad'].'"><br /><br />';
        echo 'Url du pad (avec http(s)://) <input type="text" name="zt_trad_padurl" size="64" value="'.$row['UrlPad_Trad'].'"><br /><br />';
        echo 'Url de publication (avec http(s)://) <input type="text" name="zt_trad_puburl" size="64" value="'.$row['UrlPub_Trad'].'"><br /><br />';
        if(Utils::getCountOfPossibleStates($row[ID_Status]) > 0)
        {
            $possibleStates = Utils::getStates($row[ID_Status]);
            echo 'Etat <SELECT name="zl_etat">';
?>
<?php while($possibleState = $possibleStates->fetch(PDO::FETCH_ASSOC)): ?>
<?php
            if(($row[ID_Status]) == $possibleState[ID_Status])
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
            echo '</SELECT><br /><br />';
        }
    
        if($row['ID_Priority'] == 0)
        {
            echo '<input type="checkbox" name="chk_trad_prio" value="prio"> Prioritaire ? <br/>';
        }
        else
        {
            echo '<input type="checkbox" name="chk_trad_prio" value="prio" checked> Prioritaire ? <br />';
        }
        echo '<input type="submit" value="Enregistrer les modifications" />';
        echo '</form>';		
		
		echo "</body>";
		echo "</html>";

        $lCon->closeConnection();
    }
?>