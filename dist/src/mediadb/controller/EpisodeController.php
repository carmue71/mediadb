<?php
/* EpisodeController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Implements the FileBaseController for epidsodes
 */

namespace mediadb\controller;

use mediadb\model\Episode;
use mediadb\repository\FileRepository;
use mediadb\repository\EpisodeRepository;
use mediadb\repository\ActorRepository;


include_once SRC_PATH.'tools/texttools.php';

class EpisodeController extends FileContainterController
{
    public $actorRepository;
    private $msFilter;
    private $msOrder;
    private $msStyle;
          
    public function __construct(EpisodeRepository $rep, ActorRepository $modRep, FileRepository $filerepository)
    {
        parent::__construct($rep, $filerepository);
        $this->currentSection = "Media Sets";
        $this->listStyle = 'plain';
        $this->successMessage="";
        $this->errorMessage="";
        $this->actorRepository = $modRep;
        $this->fileRepository = $filerepository;
        $this->fileOffset=0;
        $this->filePageSize=24;
        $this->pixOffset=0;
        $this->pixPageSize=24;
        
        
        if (isset($_COOKIE['msstyle'])) {
            $this->msStyle = $_COOKIE['msstyle'];
        } else
            $this->msStyle = "plain";
        
        if (isset($_COOKIE['msfilter'])) {
            $this->msFilter = $_COOKIE['msfilter'];
        } else
            $this->msFilter = "";
        
        if (isset($_COOKIE['msorder'])) {
            $this->msOrder = $_COOKIE['msorder'];
        } else
            $this->msOrder = "Title";
    }
    
    private function getBodymodifier(Episode $ms){
        if ( isset($ms->Wallpaper) && $ms->Wallpaper != "" ){
            return " style=\""
                ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$ms->Wallpaper}') no-repeat center center fixed;"
                ."background-size: cover;\"";
        } else { 
            $wallpaper = $this->repository->getChannelWallpaper($ms->REF_Channel);
          return " style=\""
                 ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$wallpaper}') no-repeat center center fixed;"
                 ."background-size: cover;\"";
        }
    }

	public function showAll()
    {
        $this->updatePageNumbers();
        
        if ( isset($_GET['filter'])){
            $this->msFilter = $_GET['filter'];
            setcookie('msfilter', $this->msFilter, time()+COOKIE_LIFETIME);
            //$this->page = 1;
        } 
        
        if ( isset($_GET['style'])){
            $this->msStyle = $_GET['style'];
            setcookie('msstyle', $this->msStyle, time()+COOKIE_LIFETIME);
            //TODO: check compatibility $this->page = 1;
        }
        
        if ( isset($_GET['order'])){
            $this->msOrder = $_GET['order'];
            setcookie('msorder', $this->msOrder, time()+COOKIE_LIFETIME);
            //TODO: check compatibility $this->page = 1;
        }
        
        //$tmpfilter = $this->getSQLFilter($this->msFilter);
        $this->filter = $this->getSQLFilter($this->msFilter);
        $this->render("listepisodes", [
            'entries' => $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize, $this->filter, $this->msOrder)
        ]);
    }

    public function show(string $title = "")
    {
        $id = $_GET['id'];
        $this->repository->addToHistory($id);
        $this->render("showepisode", ['entry' => $this->repository->find($id), 
            'actors' => $this->actorRepository->findActorsForEpisode($id)], $title);
    }
    
    public function showfiles(){
        $id = $_GET['id'];
        $this->handleFileOptions();
        //var_dump($this->fileFilter);
        $files = $this->fileRepository->findFiles($id, $this->sqlFileFilter, $this->sqlFileOrder, $this->fileOffset, $this->filePageSize);
        $this->render("showfiles", ['entry' => $this->repository->find($id), 'files' => $files, 'offset'=>$this->fileOffset]);
        
        $_SESSION['filequery_filter'] = $this->sqlFileFilter;
        $_SESSION['filequery_order'] = $this->sqlFileOrder;
        $_SESSION['filequery_pos'] = $this->fileOffset;
    }
    
    public function showpix(){
        $id = $_GET['id'];
        $this->handlePixOptions();
        //var_dump($this->fileFilter);
        $files = $this->fileRepository->findFiles($id, $this->sqlPixFilter, $this->sqlPixOrder, $this->pixOffset, $this->pixPageSize);
        $this->render("showpix", ['entry' => $this->repository->find($id), 'files' => $files, 'offset'=>$this->pixOffset]);
        
        $_SESSION['filequery_filter'] = $this->sqlPixFilter;
        $_SESSION['filequery_order'] = $this->sqlPixOrder;
        $_SESSION['filequery_pos'] = $this->pixOffset;
    }
    
    public function showMovies(){
        $id = $_GET['id'];
        $movies = $this->fileRepository->findFilesForMediaset($id, 3);
        if ( count($movies) > 0 ){
            $this->render("showmovie", ['entry' => $this->repository->find($id), 'files' => $movies]);
        } else {
            $this->showfiles();
        }
    }
    
    public function edit(){
        $id = $_GET['id'];
        $this->render("edit", ['entry' => $this->repository->find($id)]);
    }
    
    public function add(){
        $ms = new Episode();
        $ms->ID_Episode = -1;
        $ms->Comment = "\r\rWith: ";
        $this->render("edit", ['entry' => $ms]);
    }
    
    public function save(){
        $id = $_POST['id'];
        $ms = new Episode();
        $ms->Title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES);
        $ms->Description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES);
        $ms->Keywords = htmlspecialchars(trim($_POST['keywords']), ENT_QUOTES);
        $ms->Published = $_POST['published'];
        $ms->REF_Channel = $_POST['ref_channel'];
        $ms->PublisherCode = htmlspecialchars(trim($_POST['publisherCode']), ENT_QUOTES);
        $ms->Link = htmlspecialchars(trim($_POST['link']), ENT_QUOTES);
        $ms->Picture = htmlspecialchars(trim($_POST['picture']), ENT_QUOTES);
        $ms->Wallpaper = htmlspecialchars(trim($_POST['wallpaper']), ENT_QUOTES);
        $ms->Comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES);
        
        $ms->fixPicture();
        $ms->fixWallpaper();
        
        $ms->ID_Episode = $id;
        
        $result = $this->repository->save($ms);
        
        if ( $result > -1 ){
            $ms = $this->repository->find($result);
            $id = $ms->ID_Episode;
            $this->successMessage="Successfully updated the episode!";
            $this->linkActors($id, $_POST['comment']);
            $this->errorMessage="";
            $this->repository->addToHistory($id);
            $this->render("showepisode", ['entry' => $ms, 'actors' => $this->actorRepository->findActorsForEpisode($id)]);
        } else {
            $this->successMessage="";
            $this->errorMessage="Could not save the updates";
            $this->render("edit", ['entry' => $ms]);
        }
    }
    
    private function contains($haystack, $needle){
        return strpos(strtoupper($haystack), $needle) !== false;
    }
    
    private function linkActors(int $id, String $comment){
        if ($comment == null || $id < 0) {
            return;
        }
        
        $pos = -1;
        $keys = ["WITH", "STARRING", "MODEL", "MODELS", "MODEL(S)"];
        foreach($keys as $k){
            if ( $this->contains($comment, $k." ") ){
                $pos = strpos(strtoupper($comment), $k)+strlen($k)+1;
                break;
            } elseif ( $this->contains($comment, $k.":") ){
                    $pos = strpos(strtoupper($comment), $k)+strlen($k)+1;
                    break;
            }                
        }
        if ( $pos == -1 ) return; // looks like no actors can be found.
        $this->infoMessageHead = "Linking Actors";
        $candidates = preg_split("/(,|&|\r)/", substr($comment, $pos),-1, PREG_SPLIT_NO_EMPTY); 
        #$candidates = explode(",", substr($comment, $pos));
        
        foreach ($candidates as $candidate) {
            $candidate = trim($candidate);
            if ( $candidate == "" )
                continue;
            $id_actor = $this->actorRepository->findActorByName($candidate);
            if ( $id_actor > -1 ){
                if ( !$this->actorRepository->isAlreadyLinked($id_actor, $id) ){
                    $this->actorRepository->linkActorToMediaset($id_actor, $id, "autogenerated by MediaDB");
                    $this->infoMessageBody=$this->infoMessageBody."<p>Found <a href='".INDEX."showactor?id={$id_actor}'><i>{$candidate}</i><a> in comment - linked!</p>";
                } //else { $this->infoMessageBody = $this->infoMessageBody.", but actor is already linked!</p>"; }                
            } else {
                $this->infoMessageBody = $this->infoMessageBody."<p>Found unknown actor: {$candidate} in comment. <a class='btn' href='".INDEX."newactor?name={$candidate}&mid={$id}'>Add</a></p>";
            }
        }
    }
 
    public function printActors($actors){
        if ( isset($actors)){      
        	foreach ($actors as $actor){
        	    ?><a
        	    class="btn btn-outline-danger btn-sm"
        	        data-toggle="tooltip"
        	            data-placement="top"
        	                data-html="true"
        	                    title="some tooltip"
        	                        href='<?php print INDEX;?>showactor?id=<?php print $actor['ID_Actor'];?>'>
        	                        <?php print $actor['Fullname']?>
				</a>
        	    
            <?php } 
        } 
    }
    
    protected function render($view, $params)
    {
        $this->currentView = $view;
        $numberOfHDVideos = 0;
        $numberOfHiVideos =0;
        $numberOfFiles = 0;
        
        $msRep = $this->repository;
        
        switch ($view) {
            case 'listepisodes':
                $this->pageTitle = 'List of Media Sets';
                include VIEWPATH.'episode/list_episodes.php';
                break;
            case 'showepisode':
                $ms = $params['entry'];
                if ( !isset($ms) || $ms==null ){
                    header('location:'.WWW.'index.php');
                    exit();
                }
                $this->pageTitle = 'Media Set '.$ms->Title;
                $actors = $params['actors'];
                
                $numberOfActors = count($actors);
                
                $numberOfMovies = $this->repository->countFiles($ms->ID_Episode, 3);
                $numberOfPix = $this->repository->countFiles($ms->ID_Episode, 2);
                $numberOfHDVideos = $this->repository->countHDVideos($ms->ID_Episode);
                $numberOfHiVideos = $this->repository->countHiVideos($ms->ID_Episode);
                $numberOfFiles = $this->repository->countFiles($ms->ID_Episode);
                
                $bodymodifier = $this->getBodymodifier($ms);
                
                include VIEWPATH.'episode/details_episode.php';
                break;
            case 'showmovie':
                $ms = $params['entry'];
                if ( !isset($ms) || $ms==null ){
                    header('location:'.WWW.'index.php');
                    exit();
                }
                $numberOfMovies = $this->repository->countFiles($ms->ID_Episode, 3);
                $numberOfPix = $this->repository->countFiles($ms->ID_Episode, 2);
                $numberOfHDVideos = $this->repository->countHDVideos($ms->ID_Episode);
                $numberOfHiVideos = $this->repository->countHiVideos($ms->ID_Episode);
                $numberOfFiles = $this->repository->countFiles($ms->ID_Episode);
                
                $this->pageTitle = 'Media Set '.$ms->Title;
                $movies = $params['files'];
                
                $bodymodifier = $this->getBodymodifier($ms);
                include VIEWPATH.'episode/episode_movie.php';
                break;
            case 'showfiles':
                $this->updatePageNumbers();
                $ms = $params['entry'];
                if ( !isset($ms) || $ms==null ){
                    header('location:'.WWW.'index.php');
                    exit();
                }
                $files = $params['files'];
                $offset= $params['offset'];
                
                $numberOfMovies = $this->repository->countFiles($ms->ID_Episode, 3);
                $numberOfPix = $this->repository->countFiles($ms->ID_Episode, 2);
                $numberOfHDVideos = $this->repository->countHDVideos($ms->ID_Episode);
                $numberOfHiVideos = $this->repository->countHiVideos($ms->ID_Episode);
                $numberOfFiles = $this->repository->countFiles($ms->ID_Episode);
                
                $this->pageTitle = 'Media Set '.$ms->Title;
                                
                $bodymodifier = $this->getBodymodifier($ms);
                
                $lastpage = ceil($this->fileRepository->countFilesForEpisode($ms->ID_Episode, $this->sqlFileFilter)/$this->pageSize);
                
                include VIEWPATH.'episode/episode_files.php';
                break;
            case 'showpix':
                $this->updatePageNumbers();
                $ms = $params['entry'];
                if ( !isset($ms) || $ms==null ){
                    header('location:'.WWW.'index.php');
                    exit();
                }
                $files = $params['files'];
                $offset= $params['offset'];
                
                $numberOfMovies = $this->repository->countFiles($ms->ID_Episode, 3);
                $numberOfPix = $this->repository->countFiles($ms->ID_Episode, 2);
                $numberOfHDVideos = $this->repository->countHDVideos($ms->ID_Episode);
                $numberOfHiVideos = $this->repository->countHiVideos($ms->ID_Episode);
                $numberOfFiles = $this->repository->countFiles($ms->ID_Episode);
                
                $this->pageTitle = 'Media Set '.$ms->Title;
                
                $bodymodifier = $this->getBodymodifier($ms);
                
                $lastpage = ceil($this->fileRepository->countFilesForEpisode($ms->ID_Episode, $this->sqlPixFilter)/$this->pageSize);
                
                include VIEWPATH.'episode/episode_pictures.php';
                break;
            case 'edit':
                $ms = $params['entry'];
                if ( !isset($ms) || $ms==null ){
                    header('location:'.WWW.'index.php');
                    exit();
                }
                $numberOfMovies = $this->repository->countFiles($ms->ID_Episode, 3);
                $numberOfPix = $this->repository->countFiles($ms->ID_Episode, 2);
                $numberOfHDVideos = $this->repository->countHDVideos($ms->ID_Episode);
                $numberOfHiVideos = $this->repository->countHiVideos($ms->ID_Episode);
                $numberOfFiles = $this->repository->countFiles($ms->ID_Episode);
                $this->pageTitle = 'Edit Media Set '.$ms->Title;
                $channels = $this->repository->getChannelList();
                include VIEWPATH.'episode/edit_episode.php';
                break;
        }
    }
    
    private function printWatchlists($id){
        $watchlists = $this->repository->getWatchListsFor($id);
        foreach($watchlists as $item){
            print "<span class='badge badge-pill badge-light' id=WatchList{$item['ID_WatchList']}_{$item['Position']}>".
                  "<a href='".INDEX."showwatchlist?id={$item['ID_WatchList']}'>".
                    "<i class='fas fa-binoculars'></i> {$item['Title']}</a>&nbsp;".
                    "<a href='#' onClick='removeFromWatchlist({$item['ID_WatchList']},{$id}, {$item['Position']})'>".
                        "<i class='fas fa-times-circle'></i>".
                    "</a></span>&nbsp;";
        }
        
    }
}