<?php
namespace model;

class UsersSQL {
  private $connection;

  public function __construct($connection) {
    $this->connection = $connection;
  }

  public function addUser($username, $password) {
    $sql = 'INSERT INTO users (username, password) VALUES ("' . $username . '", "' . $password . '");';
    $this->connection->query($sql);
  }

  public function getUser($username) {
    $sql = 'SELECT password FROM users WHERE username="' . $username . '";';
    return $this->connection->query($sql);
  }

  public function getUsers() {
    $sql = 'SELECT username FROM users';
    return $this->connection->query($sql);
  }

  public function getUserId($username) {
    $sql = 'SELECT user_id AS "id" FROM users WHERE username="' . $username . '";';
    return $this->connection->query($sql);
  }

  public function getUsersOrderedByHighestOverallXpGained() {
    $sql = 'SELECT u.username, x.end_overall_xp AS "overall" FROM users u JOIN xp_trackers x ON u.user_id=x.creator_id WHERE x.end_overall_xp=(
      SELECT MAX(xp.end_overall_xp) FROM users us JOIN xp_trackers xp ON us.user_id=xp.creator_id WHERE us.user_id=u.user_id
    ) ORDER BY x.end_overall_xp DESC';
    return $this->connection->query($sql);
  }

  public function getUsersOrderedByHighestCombinedOverallXp() {
    $sql = 'SELECT u.username, SUM(end_overall_xp) AS "overall" FROM users u JOIN xp_trackers x ON u.user_id=x.creator_id GROUP BY u.user_id ORDER BY x.end_overall_xp DESC';
    return $this->connection->query($sql);
  }

  public function getUsersOrderedByMostTrackings() {
    $sql = 'SELECT nrOfTrackings.username, nrOfTrackings.overall AS "overall" FROM (
      SELECT u.username, COUNT(x.xp_id) AS "overall" FROM users u JOIN xp_trackers x ON u.user_id=x.creator_id GROUP BY u.username
    ) AS nrOfTrackings ORDER BY nrOfTrackings.overall DESC';
    return $this->connection->query($sql);
  }
}
?>