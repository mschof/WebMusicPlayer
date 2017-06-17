<?php

class UserUtils
{
  private static function init()
  {
    // ...
  }

  public static function userNameSanitize($name)
  {
    $name_filtered = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    return $name_filtered;
  }

  public static function passwordHash($password)
  {
    $pwd_hash = password_hash($password, PASSWORD_BCRYPT);
    return $pwd_hash;
  }

  public static function checkLogin($user_name, $password)
  {
    // Return false if user does not exist
    if(self::userExists($user_name) == false)
      return false;
    
    // Get password hash from database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    $pwd_hash = "";

    // Statement
    $query = "SELECT password_hash FROM User WHERE name=:name LIMIT 1";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':name', $user_name, SQLITE3_TEXT);
      $result = $stmt->execute();

      // Get password hash
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        $pwd_hash = $entry['password_hash'];
        break;
      }
    }
    else {
      return false;
    }

    // Return false if password does not match
    if(password_verify($password, $pwd_hash) == false)
      return false;

    return true;
  }

  public static function adminCount()
  {
    $admin_count = 0;

    // Open database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT COUNT(*) as count FROM User WHERE role=:role";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':role', 0, SQLITE3_INTEGER);
      $result = $stmt->execute();
      $row = $result->fetchArray();
      $admin_count = $row['count'];
    }
    else {
      return false;
    }

    return $admin_count;
  }

  public static function userExists($name)
  {
    // TODO: if an error occurs the output becomes interpreted as "user does not exist", fix this!
    $user_exists = false;

    // Check database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT ID FROM User WHERE name=:name LIMIT 1";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':name', $name, SQLITE3_TEXT);
      $result = $stmt->execute();

      // Count
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        $user_exists = true;
        break;
      }
    }
    else {
      return false;
    }

    return $user_exists;
  }

  public static function getUserRole($name)
  {
    $user_role = 1;

    // Open database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;

    // Statement
    $query = "SELECT role FROM User WHERE name=:name LIMIT 1";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':name', $name, SQLITE3_TEXT);
      $result = $stmt->execute();

      // Count
      while($entry = $result->fetchArray(SQLITE3_ASSOC)) {
        $user_role = $entry['role'];
        break;
      }

    }
    else {
      return false;
    }

    return $user_role;
  }

  public static function userAdd($name, $password, $role)
  {
    // Open database
    global $DB_FULL_PATH;
    $db = new SQLite3($DB_FULL_PATH);
    if($db == false)
      return false;
    
    $pwd_hash = self::passwordHash($password);

    // Statement
    $query = "INSERT INTO User (name, password_hash, role) VALUES (:name, :password_hash, :role)";
    $stmt = $db->prepare($query);
    if($stmt) {
      $stmt->bindValue(':name', $name, SQLITE3_TEXT);
      $stmt->bindValue(':password_hash', $pwd_hash, SQLITE3_TEXT);
      $stmt->bindValue(':role', $role, SQLITE3_INTEGER);
      $stmt->execute();
    }
    else {
      return false;
    }

    return true;
  }
}

?>
