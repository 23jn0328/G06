<?php
    require_once 'DAO.php';

class Event
    {
        public string $EMID;   
        public string $EID;       
        public string $EventMemberName;  
        
    }

    class EventMemberDAO
    {
        public function getEventMembers(string $EID)
        {
            $dbh=DAO::get_db_connect();

            $sql="SELECT em.id AS EMID, 
                em.event_id AS EID, 
                m.name AS EventMemberName
                FROM 
                event_members em
                INNER JOIN members m 
                ON em.member_id = m.id
                WHERE em.event_id = :eventId";

            
        }
    }

