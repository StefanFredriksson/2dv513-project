<?php
namespace controller;

class RuneScapeController extends BaseController {
  public function __construct($connection) {
    parent::__construct($connection);
  }

  public function handleTracking() {
    $stats = $this->runescapeView->getStatsToTrack();
    $userId = $this->usersSQL->getUserId($_SESSION["username"]);
    $userId = $userId->fetch_assoc();
    $userId = $userId["id"];
    $this->runescapeSQL->saveStats($userId, $_GET["name"], $stats, time());
    
    $this->baseView->redirectToProfilePage();
  }
}
?>