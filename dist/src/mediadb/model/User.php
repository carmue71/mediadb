<?php
namespace mediadb\model;

/* User.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: User model for MediaDB
 */ 

class User extends AbstractModel
{
    public $ID_User;
    public $Login;
    public $Password;
    public $Name;
    public $EMail;
    public $Role;
    public $Avatar;
    
    public function setPassword(String $pwd){
        $this->Password = md5(SALT.$pwd);
    }
    
    public function checkPassword(String $pwd){
        $input = md5(SALT.$pwd);
        return $input == $this->Password;
    } 
}

