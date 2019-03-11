<?php

class Pages extends Controller {

  function Pages()
  {
    parent::Controller();
    $this->auth->restrict();
    $this->load->model('pages_model');
    $this->load->library('validation');
  }

  function Index()
  {
    $data['main_content'] = 'pages/index';
    $data['title'] = 'Paginaoverzicht';
    $data['filter_order_Dir'] = $this->input->post('filter_order_Dir');
    //$data['pageRows'] = $this->pages_model->getPages();
    $data['pageData'] =$this->pages_model->getPages();
    //$data['moduleIcon'] = getModuleIcon();

    if(count($_POST) > 0) {
      $this->adminForm();
    }

    $this->load->view('template', $data);
  }

  function choosetype()
  {
    $data['main_content'] = 'pages/chooseType';
    $data['title'] = 'Selecteer paginatype';
    //$data['page'] = $this->pages_model->getPageInfo($this->uri->segment(3));
    //$data['pages'] = $this->pages_model->getParentPages();
    //$data['modules'] = $this->pages_model->getModules();
    //$data['moduleID'] = (int)$this->uri->segment(5);
    //$data['parentID'] = (int)$this->uri->segment(4);

    $this->load->view('template', $data);
  }

  function add()
  {
    $data['main_content'] = 'pages/editPage';
    $data['title'] = 'Pagina toevoegen';
    $data['page'] = '';
    $data['pages'] = $this->pages_model->getParentPages();
    $data['modules'] = $this->pages_model->getModules();
    $data['moduleID'] = (int)$this->uri->segment(5);
    $data['parentID'] = (int)$this->uri->segment(4);


    if(count($_POST) > 0) {
      $this->adminForm();
    }

    $this->load->view('template', $data);
  }

  function edit()
  {
    $data['main_content'] = 'pages/editPage';
    $data['title'] = 'Pagina bewerken';
    $data['page'] = $this->pages_model->getPageInfo($this->uri->segment(3));
    $data['pages'] = $this->pages_model->getParentPages();
    $data['modules'] = $this->pages_model->getModules();
    $data['moduleID'] = (int)$this->uri->segment(5);
    $data['parentID'] = (int)$this->uri->segment(4);

    $this->load->view('template', $data);
  }

  function adminForm()
  {
    $checkedIDs = $this->input->post('cid');
    $hiddenIDs = $this->input->post('hid');
    $orderIDs = $this->input->post('order');
    $task = $this->input->post('task');

    if(is_array($checkedIDs) && count($checkedIDs) > 0)
    {
      if($task == 'setdefault') {
        $this->pages_model->setDefault($checkedIDs);
      } else if($task == 'publishpage') {
        $this->pages_model->publishPage($checkedIDs);
      } else if($task == 'unpublishpage') {
        $this->pages_model->unpublishPage($checkedIDs);
      }
    } else if(is_array($hiddenIDs) && count($hiddenIDs) > 0) {
      print($task);
      var_dump(($task == 'orderdown' || $task == 'orderup' || $task == 'saveorder'));
      if(($task == 'orderdown' || $task == 'orderup' || $task == 'saveorder')) {
        $this->pages_model->orderPage($hiddenIDs, $orderIDs);
      }
    }
  }
}

?>