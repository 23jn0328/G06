
<?php
    require_once 'DAO.php';

class Event
    {
        public string  $EMID;   
        public string $EID;       
        public string $EventMemberName;  
    }
    
    class EventMemberDAO
    {
        public function getMembers(string $EID){
            $dbh=DAO::get_db_connect();
             
        }
    }