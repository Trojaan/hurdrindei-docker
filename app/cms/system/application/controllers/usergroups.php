<?php

class Usergroups extends Controller {

	function Usergroups()
	{
		parent::Controller();
    $this->auth->restrict();
	}

  function Index()
  {
    $data['main_content'] = 'users/groups';
    $data['title'] = 'Gebruikersgroepen';
    $this->load->view('template', $data);
  }

}

?>