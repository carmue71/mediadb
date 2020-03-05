<?php

/* KeywordController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Frontend logic for handling keywords
 */

namespace mediadb\controller;

class KeywordController extends AbstractController
{

    public function __construct($rep)
    {
        parent::__construct($rep);
        $this->currentSection = "Keywords";
        // TODO - Insert your code here
    }

    public function showAll()
    {
        $keywords = $this->repository->getAll();
        $this->renderPage("listkeywords", ['keywords' => $keywords ]);
        // TODO - Insert your code here
    }

    public function show(String $title = "")
    {
        $key = $_GET['key'];
        $this->renderPage("showkeyword", ['key'=>$key]);
    }

    protected function render($view, $params)
    {
        if ( $view == 'listkeywords'){ 
            $this->showList($params);
            return true;
        }
        elseif ( $view == 'showkeyword'){
            if ( isset($params['key']) ){
                $this->showKeyword($params['key']);
                return true;
            }
        }
        var_dump($view);
        print("<br>");
        var_dump($params);
        return false;
    }
    
    protected function showList($params){
        include VIEWPATH.'keywords/list_keywords.php';
    }
    
    private function showKeyword(String $key)
    {
        $keyword = $this->repository->find($key);
        
        $episodes = $keyword->episodes;
        $actors = $keyword->actors;
        
        include VIEWPATH.'keywords/details_keyword.php';
    }
}

