<?php

/* WatchlistController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Frontend handling for watchlists
 */

namespace mediadb\controller;

use mediadb\model\WatchList;

include_once SRC_PATH.'tools/texttools.php';

class WatchListController extends AbstractController
{
    private $episodeRepository;
    
    private $msFilter;
    private $msOrder;
    private $msStyle;
    private $msLastPage;

    public function __construct($rep, $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
        $this->msLastPage = -1;
        parent::__construct($rep);
        $this->currentSection = "WatchList";
        
        if ( isset($_COOKIE['watchlist_msstyle']) ){
            $this->msStyle = $_COOKIE['watchlist_msstyle'];
        } else
            $this->msStyle = "plain";
        if (isset($_COOKIE['watchlist_msfilter'])) {
            $this->msFilter = $_COOKIE['watchlist_msfilter'];
        } else
            $this->msFilter = "";
        
        if (isset($_COOKIE['watchlist_msorder'])) {
            $this->msOrder = $_COOKIE['watchlist_msorder'];
        } else
            $this->msOrder = "Title";
    }
    
    public function showAll()
    {
        $this->updatePageNumbers();
        $this->render("list", ['entries' => $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize, "","Title") ]);
    }

    public function show(String $title="")
    {
        $id = $_GET['id'];
        $this->render("show", ['entry' => $this->repository->find($id)]);
    }
    
    public function listContent(){
        $this->updatePageNumbers();
        $id = $_GET['id'];
        
        $arr_cookie_options = array ('expires' => time()+COOKIE_LIFETIME,  'samesite' => 'Strict');
        
        if ( isset($_GET['filter'])){
            if ( $this->msFilter != $_GET['filter'] ){
                $this->msFilter = $_GET['filter'];
                setcookie('watchlist_msfilter', $this->msFilter, $arr_cookie_options);
                $this->page = 1;
            }
        }
        
        if ( isset($_GET['style'])){
            if ( $_GET['style'] != $this->msStyle ){
                $this->msStyle = $_GET['style'];
                setcookie('watchlist_msstyle', $this->msStyle, $arr_cookie_options);
                $this->page = 1;
            }
        }
        
        if ( isset($_GET['order'])){
            if ( $this->msOrder != $_GET['order'] ){
                $this->msOrder = $_GET['order'];
                setcookie('watchlist_msorder', $this->msOrder, $arr_cookie_options);
                $this->page = 1;
            }
        }
        $this->episodeRepository->setFilterFromSelection($this->msFilter);        
        $this->render("content", ['entry' => $this->repository->find($id)]);
        
    }
    
    
    public function save(){
        $id = $_POST['id'];
        if ( $id > -1 ){
            //echo ('<p>Updating WatchList</p>');
            if ( $this->repository->update($id) ){
                $this->successMessage="Successfully updated your watch list!";
                $this->errorMessage="";
                $wl = $this->repository->find($id);
                $this->render("show", ['entry' => $wl]);
            } else {
                $this->successMessage="";
                $this->errorMessage="Could not save the updates";
                $wl = $this->repository->find($id);
                $this->render("edit", ['entry' => $wl]);
            }
        } else {
            //echo ('<p>Inserting new WatchList</p>');
            $wl = $this->repository->insert();
            if ( $wl ){
                //echo ('<p>success</p>');
                $this->successMessage="Successfully updated the watch list!";
                $this->errorMessage="";
                $this->render("show", ['entry' => $wl]);
            } else {
                $this->successMessage="";
                $this->errorMessage="Could not save the updates";
                $model = new WatchList();
                $model->ID_WatchList = -1;
                $this->render("edit", ['entry' => $wl]);
            }
        }
    }
    
    public function edit(){
        $id = $_GET['id'];
        $this->renderPage("edit", ['entry' => $this->repository->find($id)]);
    }
    
    public function add(){
        $wl = new WatchList();
        $wl->ID_WatchList = -1;
        $this->renderPage("edit", ['entry' => $wl]);
    }
    
    public function addEpisode(){
        
    }

    protected function render($view, $params)
    {
        $this->currentView = $view;
        $numberOfSets = -1;
        switch ( $view ){
            case 'show':
                $wl = $params['entry'];
                $episodes = $this->episodeRepository->getEpisodesForWatchList($wl->ID_WatchList, "", "Added", 3, 0);
                //TODO: add models to the episodes
                $this->pageTitle = "Watch List {$wl->Title}";
                $numberOfSets = $this->episodeRepository->countEpisodesForWatchList($wl->ID_WatchList, "");
                include VIEWPATH.'watchlist/details_watchlist.php';
                break;
            case 'content':
                $this->currentView = "listepisodesforwatchlist";
                $wl = $params['entry'];
                $tmpfilter = $this->getSQLFilter($this->msFilter);
                $episodes = $this->repository->listEpisodes($wl->ID_WatchList,$this->pageSize,($this->page-1)*$this->pageSize);
                //$episodes = $this->episodeRepository->getEpisodesForWatchList($wl->ID_WatchList, $tmpfilter, 
                //    $this->msOrder,$this->pageSize,($this->page-1)*$this->pageSize);
                //
                $numberOfSets = $this->episodeRepository->countEpisodesForWatchList($wl->ID_WatchList, "");
                $this->msLastPage = ceil($this->episodeRepository->countEpisodesForWatchList($wl->ID_WatchList, $tmpfilter)/$this->pageSize);
                $this->pageTitle = "Watch List {$wl->Title}";
                include VIEWPATH.'watchlist/wl_episodes.php';
                break;
            case 'list':
                $numberOfSets = -1;
                if (isset($params['entries'])) {
                    $watchlists = $params['entries'];
                    $this->pageTitle = "List of your Watch Lists";
                    include VIEWPATH.'watchlist/list_watchlists.php';
                }
                break;
            case 'edit':
                $numberOfSets = -1;
                if (isset($params['entry'])) {
                    $wl = $params['entry'];
                    $this->pageTitle = "Edit Watch List ". $wl->Title;
                    include VIEWPATH.'watchlist/edit_watchlist.php';
                }
                break;
            default:
                var_dump($view);
                var_dump($params);
                break;
        }
    }      
 }//---eoc