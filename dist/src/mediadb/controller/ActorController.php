<?php

/* ActorController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Implements the FileBaseController for actors and actresses
 */

namespace mediadb\controller;

use mediadb\model\Actor;

class ActorController extends FileContainterController
{
    private $episodeRepository;
    private $actorRepository;
    //---- for displaying episodes
    private $msFilter;
    private $msOrder;
    private $msStyle;
    private $msLastPage;
    
    public function __construct($rep, $episoderep, $filerep)
    {
        parent::__construct($rep, $filerep);
        
        $this->episodeRepository = $episoderep;
        $this->actorRepository = $this->repository; 
                
        $this->currentSection = "Actors";
        
        if (isset($_COOKIE['actor_msstyle'])) {
            $this->msStyle = $_COOKIE['actor_msstyle'];
        } else
            $this->msStyle = "plain";
        if (isset($_COOKIE['actor_msfilter'])) {
            $this->msFilter = $_COOKIE['actor_msfilter'];
        } else
            $this->msFilter = "";
        
        if (isset($_COOKIE['actor_msorder'])) {
            $this->msOrder = $_COOKIE['actor_msorder'];
        } else
            $this->msOrder = "Title";
        
        //----- ActorList -----------------------------------------
        
        if (isset($_COOKIE['actorstyle'])) {
            $this->actorStyle = $_COOKIE['actorstyle'];
        } else {
            $this->actorStyle = "List";
        }
        
        if (isset($_COOKIE['actorfilter'])) {
            $this->actorFilter = $_COOKIE['actorfilter'];
        } else
            $this->actorFilter = "";
        
        if (isset($_COOKIE['actororder'])) {
            $this->actorOrder = $_COOKIE['actororder'];
        } else
            $this->actorOrder = "Fullname";
     }

    public function showAll()
    {
        $this->updatePageNumbers();
        if ( isset($_GET['filter'])){
            $this->actorFilter = $_GET['filter'];
            setcookie('actorfilter', $this->actorFilter, time()+COOKIE_LIFETIME);
            //$this->page = 1;
        }
        
        if ( isset($_GET['style'])){
            $this->actorStyle = $_GET['style'];
            setcookie('actorstyle', $this->actorStyle, time()+COOKIE_LIFETIME);
            //TODO: check compatibility $this->page = 1;
        }
        
        if ( isset($_GET['order'])){
            $this->actorOrder = $_GET['order'];
            setcookie('actororder', $this->actorOrder, time()+COOKIE_LIFETIME);
            //TODO: check compatibility $this->page = 1;
        }
        
        switch ($this->actorFilter){
            case 'Female': $this->sqlActorFilter = " Gender = 'F' "; break;
            case 'Male': $this->sqlActorFilter = " Gender = 'M' "; break;
            case 'Other': $this->sqlActorFilter = " Gender <> 'F' AND  Gender <> 'M' "; break;
            case 'Top': $this->sqlActorFilter = " Rating > 4"; break;
            case 'All':
            default:
                $this->sqlActorFilter = "";
        }
        $ps = $this->pageSize;
        $actors = $this->repository->getAll($ps, ($this->page - 1) * $ps, $this->sqlActorFilter, $this->actorOrder);
        $this->renderPage("listactors", ['entries' => $actors]);
    }
    
    public function show(String $title = "")
    {
        $this->showActor($_GET['id'], $title);
    }
     
    private function showActor(int $id, $title){
        
        $entries = $this->episodeRepository->getEpisodesForActor($id, "", "Added", 3, 0);
        
        $this->renderPage("showactor", ['entry' => $this->repository->find($id), 'entries' => $entries,
            'numberOfSets' => $this->episodeRepository->countEpisodesForActor($id, "") ], $title);
    }

    public function listepisodesforactor(){
        $id = $_GET['id'];
        if ( isset($id) && $id > -1){
            
            $id = $_GET['id'];
            
            if ( isset($_GET['filter'])){
                if ( $this->msFilter != $_GET['filter'] ){
                    $this->msFilter = $_GET['filter'];
                    setcookie('actor_msfilter', $this->msFilter, time()+COOKIE_LIFETIME);
                    $this->page = 1;
                }
            }
            
            if ( isset($_GET['style'])){
                if ( $_GET['style'] != $this->msStyle ){
                    $this->msStyle = $_GET['style'];
                    setcookie('actor_msstyle', $this->msStyle, time()+COOKIE_LIFETIME);
                    $this->page = 1;
                }
            }
            
            if ( isset($_GET['order'])){
                if ( $this->msOrder != $_GET['order'] ){
                    $this->msOrder = $_GET['order'];
                    setcookie('actor_msorder', $this->msOrder, time()+COOKIE_LIFETIME);
                    $this->page = 1;
                }
            }
            $actor = $this->repository->find($id);
            $tmpfilter = $this->getSQLFilter($this->msFilter);
            
            $entries = $this->episodeRepository->getEpisodesForActor($id, $tmpfilter, $this->msOrder, $this->pageSize, ($this->page-1)*$this->pageSize);
            
            $this->msLastPage = ceil($this->episodeRepository->countEpisodesForActor($id, $tmpfilter)/$this->pageSize);
            $this->pageTitle = "Actor {$actor->Fullname}";
            
            $this->render("listepisodesforactor", ['entries' => $entries, 'actor' => $actor,
                'numberOfSets' => $this->episodeRepository->countEpisodesForActor($id, "")]);
        }
    }
    
    public function add(){
        $actor = new Actor();
        $msid = ( isset($_GET['mid']) )?$_GET['mid']:-1;
        $actor->ID_Actor = -1;
        $actor->Keywords="more-info";
        if ( isset($_GET['name']) )
            $actor->Fullname = $_GET['name'];
        $this->render("edit", ['entry' => $actor, 'episodeid' => $msid]);
    }
    
    public function edit(){
        $id = $_GET['id'];
        if ( isset($id) && $id > -1)
            $this->render("edit", ['entry' => $this->repository->find($id),'episodeid' =>-1]);
        else
            $this->add();
    }
    
    public function save(){
        $actor = new Actor();
        
        $actor->ID_Actor = $_POST['id'];
        $actor->Fullname = $_POST['fullname'];
        $actor->Aliases=$_POST['aliases'];
        $actor->Gender=$_POST['gender'];
        $actor->Description=$_POST['description'];
        $actor->Mugshot=$_POST['mugshot'];
        $actor->Wallpaper=$_POST['wallpaper'];
        $actor->Keywords=$_POST['keywords'];
        $actor->Twitter=$_POST['twitter'];
        $actor->Website=$_POST['website'];
        $actor->Thumbnail=$_POST['thumbnail'];
        $actor->Sites=$_POST['sites'];
        $actor->Data=$_POST['moddata'];
        
        $episodeid = (isset($_POST['msid']))?$_POST['msid']:-1;
                
        $actor->fixMugshot();
        $actor->fixWallpaper();
        $actor->fixThumbnail();
        
        $this->repository->save($actor);
        
        if ( $actor->ID_Actor > -1) {
            $this->successMessage = "Successfully updated the actor!";
            $this->errorMessage = "";
            
            if ( $episodeid > 0){
                $this->repository->linkActorToEpisode($actor->ID_Actor, $episodeid, "Added by saveactor");
            }
            //$this->render("showactor", ['entry' => $actor]);
            $this->showActor($actor->ID_Actor, $actor->Fullname);
        } else {
            $this->successMessage = "";
            $this->errorMessage = "Could not save the updates";
            $actor = $this->repository->find($id);
            $this->render("edit", [
                'entry' => $actor
            ]);
        }
    }
    
    public function tweetsfromactor(){
        $id = $_GET['id'];
        $actor = $this->repository->find($id);
        $this->renderPage("tweetsfromactor", ['entry' => $actor, 
            'numberOfSets' => $this->episodeRepository->countEpisodesForActor($id, "") ], "Tweets from ".$actor->Fullname);
    }
    
    public function showfiles(){
        $id = $_GET['id'];
    
        $this->handleFileOptions();
        
        $files = $this->fileRepository->findFilesForActor($id, 
                $this->sqlFileFilter, 
                $this->sqlFileOrder, 
                $this->fileOffset, 
                $this->filePageSize);
        
        $this->render("showfiles", [
            'entry' => $this->repository->find($id),
            'files' => $files, 
            'numberOfSets' => $this->episodeRepository->countEpisodesForActor($id, ""),
            'currentNumberOfFiles' => $this->fileRepository->countFilesForActor($id, $this->sqlFileFilter),
            'offset' => $this->fileOffset
        ], ""); 
    }

    protected function render($view, $params)
    {
        $this->currentView = $view;
        $this->pageTitle = null;
        $totalNumberOfFiles = -1;
        $numberOfSets = -1;
        $msRep = $this->episodeRepository;
        
        switch ( $view ){
            case 'showactor':
                if (isset($params['entry'])) {
                    $actor = $params['entry'];
                    $sets = $params['entries'];
                    $numberOfSets = $params['numberOfSets'];
                    $totalNumberOfFiles = $this->fileRepository->countFilesForActor($actor->ID_Actor, ""); //For File-Badge
                    $this->pageTitle = $actor->Fullname . " TAG's MediaDB";
                    include VIEWPATH.'actors/details_actor.php';
                } break;
            case 'listactors':
                if (isset($params['entries'])) {
                    $numberOfSets = -1;
                    $this->pageTitle = "List of Actors in TAG's MediaDB";
                    include VIEWPATH.'actors/list_actors.php';
                }
                break;
            case 'edit':
                if (isset($params['entry'])) {
                    $actor = $params['entry'];
                    $episodeid = $params['episodeid'];
                    $numberOfSets = -1;
                    if ( $actor->Fullname != "" )
                        $this->pageTitle = $actor->Fullname . " TAG's MediaDB";
                    else 
                        $this->pageTitle = "New Actor - TAG's MediaDB";
                    include VIEWPATH.'actors/edit_actor.php';
                }
                break;
            case 'listepisodesforactor':
                if (isset($params['entries']) && isset($params['actor']) ) {
                    $this->updatePageNumbers();
                    $actor = $params['actor'];
                    $sets = $params['entries'];
                    $numberOfSets = $params['numberOfSets'];
                    $totalNumberOfFiles = $this->fileRepository->countFilesForActor($actor->ID_Actor, ""); //For File-Badge
                    $this->pageTitle = $actor->Fullname . " TAG's MediaDB";
                    include VIEWPATH.'actors/actor_episodes.php';
                }
                break;
                
            case 'tweetsfromactor':
                if (isset($params['entry'])) {
                    $actor = $params['entry'];
                    $numberOfSets = $params['numberOfSets'];
                    $totalNumberOfFiles = $this->fileRepository->countFilesForActor($actor->ID_Actor, ""); //For File-Badge
                    $this->pageTitle = $actor->Fullname . " TAG's MediaDB";
                    include VIEWPATH.'actors/actor_tweets.php';
                }
                break;
            case 'showfiles':
                if (isset($params['entry'])) {
                    $this->updatePageNumbers();
                    $actor = $params['entry'];
                    $numberOfSets = $params['numberOfSets'];
                    $currentNumberOfFiles = $params['currentNumberOfFiles'];
                    $totalNumberOfFiles = $this->fileRepository->countFilesForActor($actor->ID_Actor, ""); //For File-Badge
                    $files = $params['files'];
                    $offset = $params['offset']; 
                    $this->pageTitle = $actor->Fullname . " TAG's MediaDB";
                    $lastpage = ceil($currentNumberOfFiles / $this->pageSize);
                    include VIEWPATH.'actors/actor_files.php';
                }
                break;
            default:
                var_dump($view);
                var_dump($params);
                break;
        }
    }
}