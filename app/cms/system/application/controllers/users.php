<?php

class Users extends Controller {

	function Users()
	{
		parent::Controller();
    $this->auth->restrict();
	}

  function Index()
  {
    $data['main_content'] = 'users/index';
    $data['title'] = 'Gebruikers';
    $this->load->view('template', $data);
  }

}

?>