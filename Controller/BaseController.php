<?php
namespace controller;

class BaseController {
  protected $baseView;
  protected $registerView;
  protected $runescapeView;
  protected $loginView;
  protected $statView;
  protected $connection;
  protected $usersSQL;
  protected $runescapeSQL;
  protected $runescape;

  public function __construct($connection) {
    $this->connection = $connection;
    $this->baseView = new \view\BaseView($this->connection);
    $this->registerView = new \view\RegisterView();
    $this->runescapeView = new \view\RuneScapeView();
    $this->loginView = new \view\LoginView();
    $this->statView = new \view\StatView($this->connection);
    $this->runescape = new \model\RuneScape();
    $this->usersSQL = new \model\UsersSQL($this->connection);
    $this->runescapeSQL = new \model\RunescapeSQL($this->connection);
  }
}
?>