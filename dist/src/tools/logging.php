<?php

namespace mediadb;

define ('MDB_LOG_NONE', -10);
define ('MDB_LOG_FATAL', -1);
define ('MDB_LOG_ERROR', '0');
define ('MDB_LOG_WARNING', '1');
define ('MDB_LOG_INFORMATION', '2');
define ('MDB_LOG_INFO', '2');
define ('MDB_LOG_DEBUG', '3');

class Logger{
    public static $logLevel = MDB_LOG_DEBUG;
    public static $logFile = '/var/lib/mediadb/mediadb.log';
    #public static $logFile = '/tmp/mdbd.log';
    public static $consoleLevel = MDB_LOG_WARNING;
    
    
    public function __construct(){
        #$this->info("Logger initialized");
    }
    
    private static function log(string $message, int $level){
        if ($level <= self::$logLevel){
            file_put_contents(self::$logFile, date('Y-m-d H:i:s')." ".$message."\n", FILE_APPEND);  //.u for ms
        }
        
        if ($level <= self::$consoleLevel){
            print(date('Y-m-d H:i:s')." ".$message."\n");  //.u for ms
        }
    }
    
    public static function info($message){
        self::log("INFO: ".$message, MDB_LOG_INFORMATION);
    }
    
    public static function warn($message){
        self::log("WARN: ".$message, MDB_LOG_WARNING);
    }
    
    public static function warning($message){
        self::log("WARN: ".$message, MDB_LOG_WARNING);
    }
    
    public static function error($message){
        self::log("ERRO: ".$message, MDB_LOG_ERROR);
    }
    
    public static function debug($message){
        self::log("DEBG: ".$message, MDB_LOG_DEBUG);
    }
    
    public static function setLogLeve($level){
        self::$logLevel = $level;
    }
}

