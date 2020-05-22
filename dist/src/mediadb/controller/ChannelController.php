<?php

/* ChannelController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Implements the Frontend for channels, channels and series
 */

namespace mediadb\controller;

use mediadb\model\Channel;

include_once SRC_PATH.'tools/texttools.php';

class ChannelController extends FileContainterController
{
    private $episodeRepository;
    private $actorRepository;
    
    private $msFilter;
    private $msOrder;
    private $msStyle;
    private $msLastPage;
    
    //---- for displaying channel list
    private $channelFilter;
    private $channelOrder;
    private $channelStyle;
    private $channelLastPage;

    public function __construct($rep, $episodeRepository, $actorRepository, $fileRepository)
    {
        $this->episodeRepository = $episodeRepository;
        $this->actorRepository = $actorRepository;
        $this->fileRepository = $fileRepository;
        $this->msLastPage = -1;
        parent::__construct($rep, $fileRepository);
        $this->currentSection = "Channels";
        
        if ( isset($_COOKIE['channel_msstyle']) ){
            $this->msStyle = $_COOKIE['channel_msstyle'];
        } else
            $this->msStyle = "plain";
        if (isset($_COOKIE['channel_msfilter'])) {
            $this->msFilter = $_COOKIE['channel_msfilter'];
        } else
            $this->msFilter = "";
        
        if (isset($_COOKIE['channel_msorder'])) {
            $this->msOrder = $_COOKIE['channel_msorder'];
        } else
            $this->msOrder = "Title";
        
        //----- ChannelList ----------------------------------------
        if (isset($_COOKIE['channelstyle'])) {
            $this->channelStyle = $_COOKIE['channelstyle'];
        } else {
            $this->channelStyle = "List";
        }

        if (isset($_COOKIE['channelfilter'])) {
            $this->channelFilter = $_COOKIE['channelfilter'];
        } else
            $this->channelFilter = "";

        if (isset($_COOKIE['channelorder'])) {
            if ($_COOKIE['channelorder'] == "TotalNumber" || $_COOKIE['channelorder'] == "UnseenNumber"){
                //TODO: Fix this
                $this->channelOrder = ""; 
            } else
                $this->channelOrder = $_COOKIE['channelorder'];
        } else
            $this->channelOrder = "Name";
    }
    
    

    public function showAll()
    {
        $this->updatePageNumbers();
        
        if ( isset($_GET['filter'])){
            $this->channelFilter = $_GET['filter'];
            \mediadb\Logger::debug("ChannelController: showAll: filter: ".$this->channelFilter);
            
            setcookie('channelfilter', $this->channelFilter, time()+COOKIE_LIFETIME);
            //$this->page = 1;
        }
        
        if ( isset($_GET['style'])){
            $this->channelStyle = $_GET['style'];
            setcookie('channelstyle', $this->channelStyle, time()+COOKIE_LIFETIME);
            \mediadb\Logger::debug("ChannelController: showAll: style: ".$this->channelStyle);
            //TODO: check compatibility $this->page = 1;
        }
        
        if ( isset($_GET['order'])){
            $this->channelOrder = $_GET['order'];
            
            setcookie('channelorder', $this->channelOrder, time()+COOKIE_LIFETIME);
            \mediadb\Logger::debug("ChannelController: showAll: order: ".$this->channelOrder);
            //TODO: check compatibility $this->page = 1;
        }
        
        switch ($this->channelFilter){
            case 'Unwatched': $this->sqlchannelFilter = " ID_Channel in (SELECT DISTINCT REF_Channel from Episode where Episode.Viewed = 0) "; break;
            case 'AllWatched': $this->sqlchannelFilter = " ID_Channel not in (SELECT DISTINCT REF_Channel from Episode where Episode.Viewed = 0) "; break;
            case 'Studios': $this->sqlchannelFilter = " StudioType = 'Studio'"; break;
            case 'Channels': $this->sqlchannelFilter = " StudioType = 'Channel'"; break;
            case 'Series': $this->sqlchannelFilter = " StudioType = 'Series'"; break;
            case 'Collections': $this->sqlchannelFilter = " StudioType = 'Collection'"; break;
            case 'All':
            default:
                $this->sqlchannelFilter = "";
        }
        
        $channels = $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize, $this->sqlchannelFilter, $this->channelOrder);
        //$channels = $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize, "","Name")
        
        $this->render("listchannels", ['entries' => $channels ]);
    }

    public function show(String $title="")
    {
        $id = $_GET['id'];
        $this->render("showchannel", ['entry' => $this->repository->find($id)]);
    }
    
    public function listEpisodesForChannel(){
        $this->updatePageNumbers();
        $id = $_GET['id'];
        
        if ( isset($_GET['filter'])){
            if ( $this->msFilter != $_GET['filter'] ){
                $this->msFilter = $_GET['filter'];
                setcookie('channel_msfilter', $this->msFilter, time()+COOKIE_LIFETIME);
                $this->page = 1;
            }
        }
        
        if ( isset($_GET['style'])){
            if ( $_GET['style'] != $this->msStyle ){
                $this->msStyle = $_GET['style'];
                setcookie('channel_msstyle', $this->msStyle, time()+COOKIE_LIFETIME);
                $this->page = 1;
            }
        }
        
        if ( isset($_GET['order'])){
            if ( $this->msOrder != $_GET['order'] ){
                $this->msOrder = $_GET['order'];
                setcookie('channel_msorder', $this->msOrder, time()+COOKIE_LIFETIME);
                $this->page = 1;
            }
        }
        $this->episodeRepository->setFilterFromSelection($this->msFilter);
        
        $this->render("listepisodesforchannel", ['entry' => $this->repository->find($id)]);
    }
    
    public function showfiles(){
        $id = $_GET['id'];
        $this->handleFileOptions();
        $files = $this->fileRepository->findFilesForChannel($id, $this->sqlFileFilter, $this->sqlFileOrder, $this->fileOffset, $this->filePageSize);
        $this->render("showfiles", ['entry' => $this->repository->find($id), 'files' => $files, 'offset'=>$this->fileOffset]);
    }
    
    
    public function save(){
        $id = $_POST['id'];
        if ( $id > -1 ){
            if ( $this->repository->update($id) ){
                $this->successMessage="Successfully updated the channel!";
                $this->errorMessage="";
                $channel = $this->repository->find($id);
                $this->render("showchannel", ['entry' => $channel]);
            } else {
                $this->successMessage="";
                $this->errorMessage="Could not save the updates";
                $channel = $this->repository->find($id);
                $this->render("edit", ['entry' => $channel]);
            }
        } else {
            $channel = $this->repository->insert();
            if ( $channel != null ){
                $this->successMessage="Successfully updated the channel!";
                $this->errorMessage="";
                $this->render("showchannel", ['entry' => $channel]);
            } else {
                $this->successMessage="";
                $this->errorMessage="Could not save the updates";
                $channel = new Channel();
                $channel->ID_Channel = -1;
                $this->render("edit", ['entry' => $channel]);
            }
        }
    }
    
    public function edit(){
        $id = $_GET['id'];
        $this->renderPage("edit", ['entry' => $this->repository->find($id)]);
    }
    
    public function add(){
        $channel = new Channel();
        $channel->ID_Channel = -1;
        $this->renderPage("edit", ['entry' => $channel]);
    }

    protected function render($view, $params)
    {
        $this->currentView = $view;
        $numberOfSets = -1;
        
        $msRep = $this->episodeRepository;
        
        switch ( $view ){
            case 'showchannel':
                $channel = $params['entry'];
                $episodes = $this->episodeRepository->getEpisodesForChannel($channel->ID_Channel, "", "Added", 3, 0);
                //TODO: add actors to the episodes
                $this->pageTitle = "Channel {$channel->Name}";
                $numberOfSets = $this->episodeRepository->countEpisodesForChannel($channel->ID_Channel, "");
                $numberOfUnseenSets = $this->episodeRepository->countEpisodesForChannel($channel->ID_Channel, " Viewed = 0 ");
                $totalNumberOfFiles = $this->fileRepository->countFilesForChannel($channel->ID_Channel, ""); //For File-Badge
                
                include VIEWPATH.'channels/details_channel.php';
                break;
                
            case 'listepisodesforchannel':
                $channel = $params['entry'];
                $tmpfilter = $this->getSQLFilter($this->msFilter);
                $episodes = $this->episodeRepository->getEpisodesForChannel($channel->ID_Channel, $tmpfilter, 
                    $this->msOrder,$this->pageSize,($this->page-1)*$this->pageSize);
                //
                //TODO: add actors to the episodes
                //limit the number of displayed ms and add pagination
                $numberOfSets = $this->episodeRepository->countEpisodesForChannel($channel->ID_Channel, "");
                $totalNumberOfFiles = $this->fileRepository->countFilesForChannel($channel->ID_Channel, ""); //For File-Badge
                $this->msLastPage = ceil($this->episodeRepository->countEpisodesForChannel($channel->ID_Channel, $tmpfilter)/$this->pageSize);
                $this->pageTitle = "Channel {$channel->Name}";
                include VIEWPATH.'channels/channel_episodes.php';
                break;
            
            case 'showfiles':
                $this->currentView='filesforchannel';
                if (isset($params['entry'])) {
                    $this->updatePageNumbers();
                    $channel = $params['entry'];
                    $numberOfSets = $this->episodeRepository->countEpisodesForChannel($channel->ID_Channel, "");
                    $currentNumberOfFiles = $this->fileRepository->countFilesForChannel($channel->ID_Channel, $this->sqlFileFilter);
                    $totalNumberOfFiles = $this->fileRepository->countFilesForChannel($channel->ID_Channel, ""); //For File-Badge
                    $files = $params['files'];
                    $offset = $params['offset'];
                    $this->pageTitle = $channel->Name . " Charly's MediaDB";
                    $lastpage = ceil($currentNumberOfFiles / $this->pageSize);
                    include VIEWPATH.'channels/channel_files.php';
                }
                break;
                
                
            case 'listchannels':
                $numberOfSets = -1;
                $currentNumberOfFiles = -1;
                $totalNumberOfFiles = -1;
                
                if (isset($params['entries'])) {
                    $channels = $params['entries'];
                    $this->pageTitle = "Channellist";
                    include VIEWPATH.'channels/list_channels.php';
                }
                break;
            case 'edit':
                $numberOfSets = -1;
                $currentNumberOfFiles = -1;
                $totalNumberOfFiles = -1;
                
                if (isset($params['entry'])) {
                    $channel = $params['entry'];
                    $this->pageTitle = "Edit Channel ". $channel->Name;
                    include VIEWPATH.'channels/edit_channel.php';
                }
                break;
            
            case 'scan':
                $channel = $params['channel'];
                $this->pageTitle = "Scanning your Channel";
                include VIEWPATH."channels/scan_channel.php";
                break;
                
            default:
                var_dump($view);
                var_dump($params);
                break;
        }
    }
    
    public function scan()
    {
        $id = $_GET['id'];
        $this->render("scan", ['channel' => $this->repository->find($id)]);
    }
 }//---eoc

