<?php

/* UserRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for user handling
 */

namespace mediadb\repository;
use PDO;

class UserRepository extends AbstractRepository
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->filter = "";
        $this->orderBy = "Name";
        $this->idColumn="ID_User";
        $this->tableName = "User";
        $this->className = $this->getClassName();
    }
    
    public function getTableName()
    { 
        return "User";
    }

    public function getClassName()
    {
        return "mediadb\\model\\User";
    }
    
    public function verifyUser(String $login, String $pwd){
        $query = 'SELECT * FROM User where Login=:login';
        $parameters = ['login'=>$login];
        $user = $this->queryFirst($query, $parameters, $this->getClassName());
        if ( $user && $user->checkPassword($pwd) )
            return $user->ID_User;
        else 
            return -1;
    }
    
    public function save($data){
        if ( $data->ID_User < 0 ){
            $query = "INSERT INTO User (Login, Password, Name, EMail, Role, Avatar) "
                ."VALUES (:login, :password, :name, :email, :role, :avatar)";
            $parameters = [
                'login' => $data->Login,
                'password' => $data->Password,
                'name' => $data->Name,
                'email' => $data->EMail,
                'role' => $data->Role,
                'avatar' => $data->Avatar
            ];
        } else {
            $query = "UPDATE User SET Login = :login, Password = :password, Name = :name, "
                ."EMail = :email, Role = :role, Avatar = :avatar WHERE ID_User = :id";
                
                $parameters = [
                    'login' => $data->Login,
                    'password' => $data->Password,
                    'name' => $data->Name,
                    'email' => $data->EMail,
                    'role' => $data->Role,
                    'avatar' => $data->Avatar,
                    'id' => $data->ID_User
                ];
        }
        if ( $this->execute($query, $parameters) ){
            if ( $data->ID_User == -1 )
                $data->ID_User = $this->pdo->lastInsertId("User");
            return true;
        }
        return false;
    }
    
    public function checkhistory(int $userid){
        $query = "SELECT ID_WatchList FROM WatchList where REF_User={$userid} and Title='History'";
        $id = $this->pdo->query($query)->fetch()['ID_WatchList'];
        if ( !id ){
            //todo: insert history
        } else {
            $_SESSION['history'] = $id;
        }
        
        $query = "SELECT ID_WatchList FROM WatchList where REF_User={$userid} and Title='WatchLater'";
        $id = $this->pdo->query($query)->fetch()['ID_WatchList'];
        if ( !id ){
            //todo: insert watchlater
        } else {
            $_SESSION['watchlater'] = $id;
        }
        
    }
}