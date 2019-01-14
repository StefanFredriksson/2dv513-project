<?php
namespace model;

class Skill {
  public $name;
  public $xp;

  public function __construct($name, $xp) {
    $this->name = $name;
    $this->xp = $xp;
  }

  public function getName() {
    return $this->name;
  }

  public function getXp() {
    return $this->xp;
  }
}
?>