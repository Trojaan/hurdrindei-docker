<?php
class User_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function checkUserLogin($username,$password,$table='cms_users')
    {
        $query = $this->db->where("username",$username);
        $query = $this->db->where("password",$password);
        $query = $this->db->limit(1,0);
        $query = $this->db->get($table);
        
        if ($query->num_rows() == 0) {
            return NULL;
        }
        
        return TRUE;
    }

    function getUserId($username, $table)
    {
        $query = $this->db->where("username",$username);
        $query = $this->db->limit(1,0);
        $query = $this->db->get($table);

        if ($query->num_rows() == 0) {
            return NULL;
        }

        $row = $query->row(); 

        return $row->uid;
    }

}
?>