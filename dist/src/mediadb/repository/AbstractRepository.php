<?php

/* AbstractRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Base class for all DB connection handlers
 */

namespace mediadb\repository;

use PDO;
use PDOException;

abstract class AbstractRepository 
{
    protected $pdo;
    protected $tableName;
    protected $className;
    protected $orderASC;
    protected $idColumn;
    
    protected $logLevel;
    public $filter;

    public function __construct(PDO $pdo)
    {
        $this->filter="";
        $this->pdo = $pdo;
        $this->orderASC = true;
    }
    
    protected function queryAll(String $query, $parameters = null, String $className = null)
    {
        try {
            if (isset($parameters)) {
                $stmt = $this->pdo->prepare($query);
                if ( !$stmt ){
                    var_dump($query);
                    return null;
                }
                $stmt->execute($parameters);
                if (isset($className)) {
                    $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
                    return $stmt->fetchAll(PDO::FETCH_CLASS);
                } else
                    return $stmt->fetchAll();
            } else {
                return $this->pdo->query($query, PDO::FETCH_CLASS, $className);
            }
        } catch (PDOException $ex) {
            print "Exception: <br />";
            var_dump($ex);
            return "Error";
        }
    }

    protected function queryFirst(String $query, array $parameters, String $className = null)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            if (isset($stmt) && $stmt->execute($parameters)) {
                if (isset($className)) {
                    $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
                    $result = $stmt->fetch(PDO::FETCH_CLASS);
                    if ( !$result ){
                        if ( $this->logLevel > 1 ){
                            print "<br>Something wrong with {$query} or Parameter:<br>";
                            var_dump($parameters);
                            print "<br>";
                            var_dump($className);
                        }
                        return null;     
                    }
                    return $result;
                }
            }
        } catch (PDOException $ex) {
            print "Exception: <br />";
            var_dump($ex);
            return "Error";
        }
    }

    public function find($id){
        if ( isset($this->idColumn) && isset($this->tableName) ){
            $query = "SELECT * FROM {$this->tableName} WHERE {$this->idColumn} = :id";
            return $this->queryFirst($query, ['id' => $id], $this->className);
        }
        return false;
    }

    public function getAll(int $limit=0, int $offset=0, String $filter="", String $orderBy=""){
        $query = "SELECT * FROM {$this->tableName} ";
        if (isset($filter) && $filter != "") {
            $query = $query . " WHERE {$filter}";
        }
        
        if (isset($orderBy) && $orderBy != "") {
            $query = $query . " ORDER BY {$orderBy}";
        }
        
        if ( $limit > 0){
            $query = $query . " LIMIT {$offset}, {$limit}";
        } 
        
        try {
            $result = $this->pdo->query($query);
            if ( $result )
                return $result->fetchAll(PDO::FETCH_CLASS, $this->className);
            else {
                print "<pre>";
                var_dump($query);
                var_dump($this->pdo->errorCode());
                var_dump($this->pdo->errorInfo());
                print "</pre>";
            }
        } catch (PDOException $ex) {
            print "Exception: <br />";
            var_dump($ex);
            return "Error";
        }
    }

    
    public function getCount(){
        try {
            $query = "SELECT count(*) AS Number FROM {$this->tableName}";
            if ( isset($this->filter) && $this->filter != ""){
                $query = $query." WHERE $this->filter";
            }
            $result = $this->pdo->query($query);
            return $result->fetch()['Number'];
        } catch (PDOException $ex) {
            print "Exception: <br />";
            var_dump($ex);
            return -1;
        }
    }
    
    public function getChannelList(){
        return $this->pdo->query("SELECT * FROM Channel ORDER by Name")->fetchAll();
    }
    
    protected function execute(String $query, $parameters){
        try {
            $stmt = $this->pdo->prepare($query);
            if ( !$stmt ){
                print "<h4>Error preparing the statement</h4>";
                print "<pre>\nQuery:";
                var_dump($query);
                print "\nParameters:";
                var_dump($parameters);
                print "\nPDO:";
                var_dump($this->pdo->errorInfo());
                print "\n</pre>";
                return false;
                
            }
            if ( !$stmt->execute($parameters) ){
                print "<h4>Error executing the statment</h4>";
                print "<pre>\nQuery:";
                var_dump($query);
                print "\nParameters:";
                var_dump($parameters);
                print "\nPDO:\n";
                var_dump($this->pdo->errorInfo());
                var_dump($this->pdo->errorCode());
                print "</pre>";
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
    }
    
    public function getFilter(){
        return $this->filter;
    }
    
    public function setFilter($f){
        $this->filter = $filter;
    }
}
