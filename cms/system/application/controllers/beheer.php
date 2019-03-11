<?php

class Beheer extends Controller {

  function login()
  {
    $data['main_content'] = 'login';
    $data['title'] = 'Login';
    $this->load->helper("form");
    $this->load->view('template', $data);
  }

}

?>