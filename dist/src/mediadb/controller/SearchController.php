<?php

/* EpisodeController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Implements the Frontend for searches
 */

namespace mediadb\controller;

use mediadb\repository\EpisodeRepository;
use mediadb\repository\ActorRepository;

class SearchController extends AbstractController
{
    
    private $ActorRepository;

    public function __construct(EpisodeRepository $rep, ActorRepository $modRep)
    {
        parent::__construct($rep);
        $this->ActorRepository =$modRep;
        $this->currentSection = "Search";
    }

    public function search(){
        //todo: paging
        $search = $_GET['search'];
        if ( isset($search)){
            switch ( $search ){
                case '#NOFILE': //Due to a typo and no reason why this should not be there
                case '#NOFILES':
                    $sets = $this->repository->findSetsWithoutFiles();
                    $Actors = $this->ActorRepository->findActorsWithoutSet();
                    break;
                case '#NOActor': //Episodes that are not linked to Actors
                case '#NOActorS':
                    $sets = $this->repository->findSetsWithoutActors();
                    $Actors=null;
                    break;
                case '#NOMOVIE': //Episodes that are not linked to Actors
                case '#NOMOVIES':
                case '#NOVIDEO':
                case '#NOVIDEOS':
                case '#NOVIDS':
                    $sets = $this->repository->findSetsWithoutMovies();
                    $Actors=null;
                    break;
                case '#MOREMOVIES': //Episodes that are not linked to Actors
                case '#MOREVIDEOS':
                case '#MOREVIDS':
                    $sets = $this->repository->findSetsWithMultipleMovies();
                    $Actors=null;
                    break;
                case '#MANYPICS': //More than 200 pics
                    $sets = $this->repository->findSetsWithManyPics();
                    $Actors=null;
                    break;
                case '#TODAY': //Sets or Actors modified today
                    $sets = $this->repository->findSetsModifiedToday();
                    $Actors=null;
                    break;
                case '+TODAY': //Sets or Actors added today
                    $sets = $this->repository->findSetsAddedToday();
                    $Actors=null;
                    break;
                case '#YESTERDAY': //Sets or Actors modified yesterday
                    $sets = $this->repository->findSetsModified(-1);
                    $Actors=null;
                    break;
                case '+YESTERDAY': //Sets or Actors added yesterday
                    $sets = $this->repository->findSetsAdded(-1);
                    $Actors=null;
                    break;
                case '#THISWEEK': //Sets or Actors modified this week
                    $sets = $this->repository->findSetsModified(-7);
                    $Actors=null;
                    break;
                case '+THISWEEK': //Sets or Actors added this week
                    $sets = $this->repository->findSetsAdded(-7);
                    $Actors=null;
                    break;
                case '#NOSITE':
                    $sets = null;
                    $Actors = $this->ActorRepository->findActorsWithoutSites();
                    break;
                case '#NOMUG':
                case '#NOMUGSHOT':
                    $sets = null;
                    $Actors = $this->ActorRepository->findActorsWithoutMugshots();
                    break;
                case '#NODATA':
                    $sets = $this->repository->findSetsWithoutData();
                    $Actors = $this->ActorRepository->findActorsWithoutData();
                    break;
                case '#PARTIALLYWATCHED':
                case '#PARTIALLY_WATCHED':
                case '#PARTWATCHED':
                    $sets = $this->repository->findPartiallyWatchedVideos();
                    $Actors = null;
                    break;
                case '#STARTEDTOWATCH':
                case '#STARTED':
                    $sets = $this->repository->findStartedToWatchVideos();
                    $Actors = null;
                    break;
                case '#MOSTLY_FINISHED':
                case '#MOSTLYFINISHED':
                    $sets = $this->repository->findMostlyFinishedVideos();
                    $Actors = null;
                    break;
                    
                    
                default:
                    $sets = $this->repository->searchEpisodes($search);
                    $Actors = $this->ActorRepository->searchActors($search);
            }
            
            $this->render('search', ['Sets' => $sets, 'Actors' => $Actors, 'search' => $search ]);
        }
    }
    
    public function showAll()
    {   
        // TODO - Insert your code here
    }

    public function show(string $title = "")
    {        
        // TODO - Insert your code here
    }

    protected function render($view, $params)
    {
        $this->pageTitle ="Search results for {$params['search']}";
        $this->currentView = $view;
        $actors = $params['Actors'];
        $sets = $params['Sets'];
        include VIEWPATH."searchresults.php";
    }
}