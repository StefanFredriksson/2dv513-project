<?php
namespace model;

class RuneScape {
  private static $url = 'https://secure.runescape.com/m=hiscore/index_lite.ws?player=';
  private $skills = array('Total', 'Attack', 'Defence', 'Strength', 'Constitution', 'Ranged', 'Prayer', 'Magic', 'Cooking', 'Woodcutting', 'Fletching', 'Fishing', 'Firemaking', 'Crafting', 'Smithing', 'Mining', 'Herblore', 'Agility', 'Thieving', 'Slayer', 'Farming', 'Runecrafting', 'Hunter', 'Construction', 'Summoning', 'Dungeoneering', 'Divination', 'Invention');

  public function getRuneScapeUser($name) {
    $user = array();
    $result = file_get_contents(self::$url . $name);

    $stats = preg_split('/[,|\s+]/', $result, -1, PREG_SPLIT_NO_EMPTY);

    $count = 0;
    while (count($user) < count($this->skills)) {
      $user[] = new Skill($this->skills[count($user)], $stats[$count + 2]);
      $count += 3;
    }

    return $user;
  }
}
?>