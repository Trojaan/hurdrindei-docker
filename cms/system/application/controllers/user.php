<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends Controller {

    function User() {
        parent::Controller();
        
        $this->load->model("user_model");
        //$this->load->model("dalia_model");
        
        $this->load->helper('url');
        $this->load->helper("security");
        $this->load->helper("form");
    }
    
    /* Checks if the password that the user gives is equal at the DB ones */
    function _check_login($username) {
        $password = md5($this->validation->password);
        
        if(!$this->user_model->checkUserLogin($username,$password,"cms_users"))
            return FALSE;
            
        return TRUE;
    }
    
    function index() {
      $data['main_content'] = 'login';
      $data['title'] = 'Login';
      $data['type'] = 'login';
      $this->load->view("template", $data);
    }

    function checkLogin()
    {
        $this->load->library('validation');
        $rules['username'] = 'trim|required|callback__check_login';
        $rules['password'] = 'trim|required';
        $this->validation->set_rules($rules);
        
        $fields['username'] = 'username';
        $fields['password'] = 'password';
        $this->validation->set_fields($fields);
        
        if ($this->validation->run()) {
            $username    = $this->validation->username;
            $uid         = $this->user_model->getUserId($username,"cms_users");

            $data = array(
                   'uid'  => $uid
               );
            
            $this->session->set_userdata("logged_in", $data);
            
            $output = '{ "success": "yes", "welcome": "Welkom" }';
        } else {
            $output = '{ "success": "no", "message": "Gebruikersnaam en wachtwoord komen niet overeen" }';
        }
        
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
    }
    
    function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('beheer/login'));
    }

}

?>