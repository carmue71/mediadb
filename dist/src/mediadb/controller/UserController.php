<?php

/* UserController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Frontend for user interactions
 */


namespace mediadb\controller;

use mediadb\model\User;

class UserController extends AbstractController
{   
    public function list()
    {
        $this->render('list', array('users' => $this->repository->getAll()));
    }

    public function detail()
    {
        $id = esc($_GET['uid']);
        $this->render('detail', ['user'=>$this->repository->find($id), 'message'=>""]);
    }
    
    public function add()
    {
        $this->pageTitle ="Benutzer hinzufügen";
        $user = new User();
        $user->ID_User = -1;
        $this->render('edit', array('user' => $user, 'message'=>""));
    }
    
    public function edit()
    {
        $this->pageTitle ="Benutzer bearbeiten";
        $id = esc($_GET['uid']);
        $this->render('edit', ['user' => $this->repository->find($id), 'message'=>""]);
    }
    
    public function save()
    {
        $user = new User();
        $user->ID_User = esc($_POST['id']);
        $created = ($user->ID_User == -1);
        $user->Login = esc($_POST['login']);
        $user->Name = esc($_POST['name']);
        $user->EMail = esc($_POST['email']);
        $user->Role = esc($_POST['role']);
        $user->Avatar = esc($_POST['avatar']);
        if ( $user->ID_User == -1 || ( $user->Password != esc($_POST['password']) ) )
            $user->setPassword(esc($_POST['password']));
        
        if ( $this->repository->save($user)){
            $successMsg = $created?"Benutzer erfolgreich angelegt":"Benutzer erfolgreich aktualisiert";
            $this->render('detail', ['user'=>$user, 'message'=>$successMsg]);            
        } else {
            $errorMsg = $created?"Fehler beim anlegen des Nutzers!":"Fehler beim aktualisieren des Nutzers";
            $this->render('edit', ['user'=>$user, 'message'=>$errorMsg]);
        }
    }
    
    public function logout(){
        unset($_SESSION['login']);
        unset($_SESSION['userid']);
        header('Location: /login.php');
    }
    
    protected function render($view, $params)
    {
        $this->currentView = $view; 
        $this->currentSection == 'Benutzer';
        
        switch ($this->currentView){
            case 'list':
                $users = $params['users'];
                $this->pageTitle ="Liste der Benutzer";
                include VIEWPATH.'user/list_users.php';
                break;
            case 'edit':
                $message = $params['message'];
                $user = $params['user'];
                include VIEWPATH.'user/edit_user.php';
                break;
            case 'detail':
                $message = $params['message'];
                $user = $params['user'];
                include VIEWPATH.'user/detail_user.php';
                break;
        }//switch
    }
    public function showAll()    { $this->list(); }

    public function show(string $title = "")    {        $this->detail();    }
}

