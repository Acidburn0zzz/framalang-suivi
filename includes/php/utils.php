<?php
    require_once dirname(__FILE__).'/../../settings.php';
    require_once dirname(__FILE__).'/db_connection_class.php';

    class Utils {
        /**
         *  Use this method to generate a link for the table titles
         **/
        public static function generateLinkWithPage($text, $order, $page)
        {
            global $order_by, $order_dir;

            $link = '<a href="?order='.$order;

            if($order_by==$order && $order_dir=='ASC')
            {
                $link .= '&inverse=true';
            }
            $link .= '&page='.$page;
            $link .= '"';
            $link .= '>'.$text.'</a>';
            
            return $link;
        }

        /**
         *  Use this method to generate a link
         **/
        public static function generateLink($text, $order)
        {
            global $order_by, $order_dir;
            
            $link = '<a href="?order='.$order;
            
            if($order_by==$order && $order_dir=='ASC')
            {
                $link .= '&inverse=true';
            }
            $link .= '"';
            $link .= '>'.$text.'</a>';
            
            return $link;
        }
        
        /**
         *Used to get the available states for the given translation status
        **/
        public static function getCountOfPossibleStates($idStatus)
        {
            $lConStatus = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
            $lConPDOStatus = $lConStatus->openConnection();

            $sqlStatus = "SELECT count(S.ID_Status) as idCount FROM STATUS S WHERE S.ID_Status > '".$idStatus."';";
            $sthStatus = $lConPDOStatus->query($sqlStatus);
            $rowStatus = $sthStatus->fetch(PDO::FETCH_ASSOC);

            $lConStatus->closeConnection();

            return $rowStatus[idCount];
        }

        /**
         *Used to get the available states for the given translation status
        **/
        public static function getPossibleStates($idStatus)
        {
            $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
            $lConPDO = $lCon->openConnection();

            $sql = "SELECT S.ID_Status, S.Name_Status FROM STATUS S WHERE S.ID_Status > '".$idStatus."';";
            $sth = $lConPDO->query($sql);

            $lCon->closeConnection();

            return $sth;
        }
        
        /**
         *Used to get the available states for the given translation status current value included
         **/
        public static function getStates($idStatus)
        {
            $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
            $lConPDO = $lCon->openConnection();
            
            $sql = "SELECT S.ID_Status, S.Name_Status FROM STATUS S WHERE S.ID_Status >= '".$idStatus."';";
            $sth = $lConPDO->query($sql);
            
            $lCon->closeConnection();
            
            return $sth;
        }
        
        /**
         *Return the number of elements in traduction table
         */
        public static function getNbTrads()
        {
            $lCon = new DatabaseConnection(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
            $lConPDO = $lCon->openConnection();
            
            $authorizedsort=array('Title_Trad', 'Name_Status', 'DateCre_Trad');
            $order_by=in_array($_GET['order'],$authorizedsort) ? $_GET['order'] : 'Title_Trad';
            
            $order_dir = isset($_GET['inverse']) ? 'DESC' : 'ASC';
            
            $sql = 'SELECT count(ID_Trad) FROM TRADUCTION T;';
            $sth = $lConPDO->query($sql);
            $count=$sth->fetch();
            
            $lCon->closeConnection();
            
            return $count[0];
        }
    }

?>