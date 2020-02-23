<?php 
define('SRC_PATH', '/opt/MediaDB/src/');

require SRC_PATH.'mediadb/conf.php';
#require '../mediadb/conf_default.php';
require 'databasetools.php';
    
if ( isset($argv[1]) && isset ($argv[2]) ){
    $password = md5(SALT.$argv[2]);
    $user = $argv[1];
    
    $query = "INSERT INTO User (Login, Password, Name, EMail, Role) "
        ."VALUES (:login, :password, :name, :email, :role)";
    $parameters = [
            'login' => $user,
            'password' => $password,
            'name' => "Admin User",
            'email' => "",
            'role' => "Administrator"
        ];
    
    try {
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare($query);
        if ( !$stmt ){
            print "Error preparing the statement";
                print "\nQuery:";
                var_dump($query);
                print "\nParameters:";
                var_dump($parameters);
                print "\nPDO:";
                var_dump($pdo->errorInfo());
                print "\n";
            return false;
       }
       if ( !$stmt->execute($parameters) ){
            print "Error executing the statment";
                print "\nQuery:";
                var_dump($query);
                print "\nParameters:";
                var_dump($parameters);
                print "\nPDO:\n";
                var_dump($pdo->errorInfo());
                var_dump($pdo->errorCode());
                return null;
        }
        return true;
    } catch (PDOException $e){
            echo "Failed: ".$e->getMessage()."\n";
            return false;
    } catch (\Exception $e){
            echo "Genral Problem: ".$e->getMessage()."\n";
            return false;
    }
} else {
    print("Please provide a username and password\n");
}
?>