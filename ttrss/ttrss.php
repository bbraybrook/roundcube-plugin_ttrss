<?php

class ttrss extends rcube_plugin {
  public $task = '?(?!login).*';

  function init() {
    $this->add_texts('localization/', false);
    $this->register_task('ttrss');
    $this->register_action('index', array($this, 'index'));
    if ($_SESSION['ttrss_init']) {
      $this->add_hook('session_destroy', array($this, 'cleanup'));
    }
    $this->add_button(array('command'=>'ttrss', 'name'=>'ttrss', 'class'=>'button-ttrss', 'classsel'=>'button-ttrss button-selected',
      'innerclass'=>'button-inner','label'=>'ttrss.ttrss'), 'taskbar');
  }

  function index() {
    $rcmail = rcube::get_instance();
    $user = $rcmail->user->get_username();
    $rcmail->output->include_script('../../plugins/ttrss/ttrss.js');
    $token = '';
    if (!$_SESSION['ttrss_init']) {
      $token = '____' . base64_encode(openssl_random_pseudo_bytes(30));
      apc_store($token,$user,60);
      $_SESSION['ttrss_init'] = true;
    }
    $rcmail->output->add_script("ttrss('$user','$token','/rss/');",'foot'); 
    $rcmail->output->send('ttrss.ttrss');
  }

  function cleanup($args) {
    $rcmail = rcube::get_instance();
    $rcmail->output->include_script('../../plugins/ttrss/ttrss.js');
    $rcmail->output->add_script("ttrss_logout('/rss/');",'foot');
  }

}
