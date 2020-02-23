<?php

function connectToDatabase(){
    try {
        $pdo = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME.';charset=utf8', DBUSER,  PASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e){
        print "Cannot connect to database - please check";
        die();
    }
}