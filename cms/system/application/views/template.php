<?php

$data['title'] = $title;

if($main_content == 'login')
  $header = 'loginHeader';
else 
  $header = 'header';

$this->load->view('includes/' . $header, $data);

$this->load->view($main_content);

$this->load->view('includes/footer');

?>