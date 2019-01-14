<?php
namespace controller;

class StatController extends BaseController {
  public function __construct($connection) {
    parent::__construct($connection);
  }

  public function stopTracking() {
    $id = $this->statView->getId();
    $info = $this->runescapeSQL->getOpenStats($id);
    $info = $info->fetch_assoc();
    $username = $info["username"];
    $currentStats = $this->runescape->getRuneScapeUser($username);
    $oldSkills = $this->statView->getSkills($info["xp"]);
    $currentSkills = $this->statView->getTrimmedStats($oldSkills, $currentStats);
    $overall = $this->getOverallXpGained($oldSkills, $currentSkills);

    $this->runescapeSQL->stopTracking($id, $currentSkills, time(), $overall);
    $this->baseView->redirectToProfilePage();
  }

  private function getOverallXpGained($oldSkills, $currentSkills) {
    $overall = 0;

    for($i = 0; $i < count($oldSkills); $i++) {
      if(count($oldSkills) == 1 && $oldSkills[$i]->getName() == "Total") {
        $overall += $currentSkills[$i]->getXp() - $oldSkills[$i]->getXp();
      } else if ($oldSkills[$i]->getName() != "Total") {
        $overall += $currentSkills[$i]->getXp() - $oldSkills[$i]->getXp();
      }
    }

    return $overall;
  }
}
?>