<?php

/* SettingsController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Frontend for settings
 * Note: Not much here yet, settings are mainly controlled via conf.php or are unfortunately hard coded.
 */

namespace mediadb\controller;


class SettingsController extends AbstractController
{   
    public function add()    {  }
    
    public function edit()
    {
        $this->pageTitle ="Edit Settings";
        $this->render('edit', ['settings' => $this->repository->find(0), 'message'=>""]);
    }
    
    public function save()
    {
        //TODO: Implement
    }
    
    protected function render($view, $params)
    {
        $this->currentView = $view; 
        $this->currentSection == 'Settings';
        
        switch ($this->currentView){
            case 'edit':
                $message = $params['message'];
                $settings = $params['settings'];
                include VIEWPATH.'settings.php';
                break;
        }//switch
    }
    public function showAll()    {  }

    public function show(string $title = "")    { }
}
