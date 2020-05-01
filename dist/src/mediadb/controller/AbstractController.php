<?php

/* AbstractController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Base class for all controllers used in mediadb
 */

namespace mediadb\controller;

abstract class AbstractController 
{    
    protected $repository;
    protected $page;
    protected $pageSize;
    protected $filter;
    protected $listStyle;
    protected $currentView;
    
    public $currentSection;
    public $successMessage;
    public $errorMessage;
    public $infoMessageHead;
    public $infoMessageBody;
    public $pageTitle;
    
    public  function __construct($rep){
        $this->repository = $rep;
        $this->page = 1; //start page counting at 1
        $this->pageSize = 24;
        $this->filter = "";
        $this->successMessage="";
        $this->errorMessage="";
        $this->infoMessageHead="";
        $this->infoMessageBody="";
        $this->pageTitle ="";
    }
    
    public function getRepository(){
        return $this->repository; 
    }
    
    abstract protected function render($view, $params);
        
    protected function renderPage($view, $params)
    {
        $this->currentView = $view;
        $this->render($this->currentView,$params);
    }
    
    protected function printPagination($lp=-1, $rep=null){
        if ( $rep == null )
            $rep = $this->repository;
        $pg = $this->page;
        if ( $lp == -1 ){
            $rep->filter = $this->filter;
            $lastpage = floor($rep->getCount()/$this->pageSize)+1;
        } else {
            $lastpage = $lp;
        }
        if ( isset($_GET['style']))
            $style = $_GET['style'];
        else
            $style = 'plain';
        include VIEWPATH.'fragments/pagination.php';
    }
    
    abstract public function showAll();
    abstract public function show(string $title="");
    
    protected function updatePageNumbers(){
        if (isset($_GET['page'])){
            //todo: check boundaries
            $this->page = $_GET['page'];
        } else {
            $this->page=1;
        }
        //todo: PageSize
    }
    
    protected function printKeywords(String $longString){
        $list = explode(",", $longString);
        foreach ($list as $key){
            $k = htmlspecialchars(trim($key));
            if ( $k <> "" )
                print ("<span class='badge badge-pill badge-light'><a class=keywordlink href='".INDEX."showkeyword?key={$k}'>
                <i class='fas fa-tag'></i> {$k}</a> </span>&nbsp;");//&nbsp;<a class=keywordlink href='#'><i class='fas fa-times-circle'></i></a> </span>&nbsp;");
        }
    }
    
    public function printActors($actors){
        if ( isset($actors)){
            foreach ($actors as $actor){
                print ("<span class='badge badge-pill badge-light'>
                                        <a class='actorlink' href='".INDEX."showactor?id={$actor['ID_Actor']}' title='{$actor['ID_Actor']}''> {$actor['Fullname']} </a></span>");
            }
        }
    }
    
    
    public function cutStr($str, int $maxlen, int $tolerance=10, String $addon=' ...'){
        if ( $str == null)
            return "";
            if ( strlen($str) <= $maxlen)
                return $str; //nothing to do
                $i = 0;
                //
                while ( $str[$maxlen-$i]<>" " && $maxlen-$i > 0 && $i < $tolerance  )
                    $i++;
                    
                    return substr($str, 0, $maxlen-$i).$addon;
    }
    
    public function esc(String $str){
        return htmlspecialchars($str, ENT_QUOTES, UTF-8);
    }
    
    protected function getSQLFilter($filter){
        switch ($filter){
            case 'Unwatched': return 'Viewed = 0';
            case 'Watched':  return 'Viewed > 0';
            case 'Top': return 'Rating > 4';
            case 'OK': return 'Rating > 3';
            case 'Average': return 'Rating = 2';
            case 'Flop': return 'Rating = 1';
            case 'Unrated': return '(Rating is null OR Rating = 0)';
            case 'Recently Added': //TODO: recently ADDED
            case 'All':
            default: return '';
        }
    }
    
    protected function getOrder($order){
        switch ( $order){
            case "Added": return 'Added ASC';
            case 'Added_DESC': return 'Added DESC';
        }
    }
}

