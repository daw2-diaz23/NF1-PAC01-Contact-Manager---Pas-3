<?php
require_once('class.Entity.php');
require_once('class.Individual.php');
require_once('class.Organization.php');

class DataManager 
{
   private static function _getConnection() {
      static $hDB;
   
      if(isset($hDB)) {
         return $hDB;
      }
   
      $host = "localhost";
      $port = "5432";
      $dbname = "php";
      $user = "postgres";
      $password = "root";
      
      $connectionString = "host=$host port=$port dbname=$dbname user=$user password=$password";
      
      $hDB = pg_connect($connectionString) or die('Unable to connect. Check your connection parameters.');
      
      return $hDB;
   }
 
  public static function getEntityData($entityID) {
    $sql = "SELECT * FROM entities WHERE entityid = $entityID";
    $res = pg_query(DataManager::_getConnection(), $sql);
    if(!$res || pg_num_rows($res) === 0) {
      die("Failed getting entity $entityID");
    }
    return pg_fetch_assoc($res);
 }

 public static function getAllEntitiesAsObjects() {
    $sql = "SELECT entityid, type from entities";
    $res = pg_query(DataManager::_getConnection(), $sql);
   
    if(!$res) {
      die("Failed getting all entities");
    }
   
    if(pg_num_rows($res) > 0) {
      $objs = array();
      while($row = pg_fetch_assoc($res)) {
        if($row['type'] == 'I') {
          $objs[] = new Individual($row['entityid']);
        } elseif ($row['type'] == 'O') {
          $objs[] = new Organization($row['entityid']);
        } else {
          die("Unknown entity type {$row['type']} encountered!");
        }
      }
      return $objs;
    } else {
      return array();
    }
  } 

}
?>
