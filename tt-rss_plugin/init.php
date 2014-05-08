<?php
class Auth_roundcube extends Plugin implements IAuthModule {

  private $host;
  private $base;

  function about() {
    return array(1.0,
      "Authenticate requests from roundcube",
      "fox",
      true);
  }

  function init($host) {
    $this->host = $host;
    $this->base = new Auth_Base();
    $host->add_hook($host::HOOK_AUTH_USER, $this);
  }

  function authenticate($login, $password) {
    if ($login) {
      if (substr($password,0,4) == '____') {
        if (apc_fetch($password) == $login) {
          apc_delete($password);
          $_SESSION["hide_hello"] = true;
          $_SESSION["hide_logout"] = true;
          $user_id = $this->base->auto_create_user($login, 'xxxxxxxx'); // this creates the user if they don't already exist, returns user_id if they do
          ttrss_error_handler('LOG_INFO', "$login logged in direct from roundcube", " auth_roundcube", 30, '');
          return $user_id;
        } else {
          ttrss_error_handler('LOG_INFO', "$login failed direct login from roundcube", " auth_roundcube", 33, '');
          sleep(1);
          return false;
        }
      } else {
        # your own custom auth routine here
        # this handles regular auth requests (ones not coming from roundcube)
        $auth = false;
        if ($auth) {
          $user_id = $this->base->auto_create_user($login, 'xxxxxxxx'); // this creates the user if they don't already exist, returns user_id if they do
          ttrss_error_handler('LOG_INFO', "$login logged in", " auth_roundcube", 30, '');
          return $user_id;
        } else {
          ttrss_error_handler('LOG_INFO', "$login failed login", " auth_roundcube", 33, '');
          return false;
        }
      }
    } else {
      // hit the login page, nothing posted
      return false;
    }
  }

  function api_version() {
    return 2;
  }

}

?>
