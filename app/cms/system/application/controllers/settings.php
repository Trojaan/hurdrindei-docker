<?php

class Settings extends Controller {

	function Settings()
	{
		parent::Controller();
    $this->auth->restrict(); // restrict this controller to editor and above
	}

  function Index()
  {
    $data['main_content'] = 'pages/settings.php';
    $data['title'] = 'Instellingen';
    $this->load->view('template', $data);
  }

}

?>