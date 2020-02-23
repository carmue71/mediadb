<?php

/* FileContainerController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Base Class for all controllers that handle files
 */

namespace mediadb\controller;

use mediadb\controller\AbstractController;
use mediadb\repository\FileRepository;

abstract class FileContainterController extends AbstractController {
    protected $fileRepository;
    
    protected $fileFilter;
    protected $sqlFileFilter;
    protected $fileOrder;
    protected $sqlFileOrder;
    protected $fileStyle;
    protected $fileOffset;
    protected $filePageSize;
    
    protected $pixFilter;
    protected $sqlPixFilter;
    protected $pixOrder;
    protected $sqlPixOrder;
    protected $pixStyle;
    protected $pixOffset;
    protected $pixPageSize;
    protected $videoVolume;

    public function __construct($rep, FileRepository $filerepository)
    {
        parent::__construct($rep);
        $this->fileRepository = $filerepository;
        $this->sqlFileFilter ="";
        $this->fileFilter ="";
        $this->fileOrder ="";
        $this->sqlFileOrder;
        $this->fileStyle ="";
        $this->fileOffset =0;
        $this->filePageSize =0;
        
        $this->sqlPixFilter ="";
        $this->pixFilter ="";
        $this->pixOrder ="";
        $this->sqlPixOrder;
        $this->pixStyle ="";
        $this->pixOffset = 0;
        $this->pixPageSize = 0;
        
        $this->videoVolume = 0.8; //80% Volume
        
        
        if (isset($_COOKIE['filestyle'])) {
            $this->fileStyle = $_COOKIE['filestyle'];
        } else {
            $this->fileStyle = "List";
        }
        
        if (isset($_COOKIE['filefilter'])) {
            $this->fileFilter = $_COOKIE['filefilter'];
            $this->setSQLFileFilter();
        } else
            $this->fileFilter = "All";
        
        if (isset($_COOKIE['fileorder'])) {
            $this->fileOrder = $_COOKIE['fileorder'];
            $this->setSQLFileOrder();
        } else {
            $this->fileOrder = "Name";
            $this->sqlFileOrder = 'REF_Device, Path, Name';
        }
        
        if (isset($_COOKIE['pixstyle'])) {
            $this->pixStyle = $_COOKIE['pixstyle'];
        } else {
            $this->pixStyle = "List";
        }
        
        if (isset($_COOKIE['pixfilter'])) {
            $this->pixFilter = $_COOKIE['pixfilter'];
            $this->setSQLPixFilter();
        } else
            $this->pixFilter = "All";
            
        if (isset($_COOKIE['pixorder'])) {
            $this->pixOrder = $_COOKIE['pixorder'];
            $this->setSQLPixOrder();
        } else {
            $this->pixOrder = "Name";
            $this->sqlPixOrder = 'REF_Device, Path, Name';
        }
        $this->filePageSize = DEFAULT_PAGESIZE;
        $this->pixPageSize = DEFAULT_PAGESIZE;
        
        if (isset($_COOKIE['VideoVolume'])) {
            $this->videoVolume = floatval($_COOKIE['VideoVolume']);
        }
    }
    
    public function handleFileOptions(){
        if (isset($_GET['filter'])) {
            $this->fileFilter = $_GET['filter'];
            setcookie('filefilter', $this->fileFilter, time() + COOKIE_LIFETIME);
            $this->setSQLFileFilter();
        } else {
            //$this->fileFilter = "";
            //setcookie('filefilter', $this->fileFilter, time() + COOKIE_LIFETIME);
            //$this->sqlFileFilter = "";
        }
        
        if (isset($_GET['style'])) {
            $this->fileStyle = $_GET['style'];
            setcookie('filestyle', $this->fileStyle, time() + COOKIE_LIFETIME);
            // TODO: check compatibility $this->page = 1;
        } //else
            
        if (isset($_GET['order'])) {
            $this->fileOrder = $_GET['order'];
            setcookie('fileorder', $this->fileOrder, time() + COOKIE_LIFETIME);
            $this->setSQLFileOrder();
        }
        
        // todo: set PageSize according to style
        
        if (isset($_GET['page'])) {
            $page = $_GET['page'] - 1;
            $this->fileOffset = $page * $this->filePageSize;
        } else
            $this->fileOffset = 0;
        
        $_SESSION['filequery_filter'] = $this->sqlFileFilter;
        $_SESSION['filequery_order'] = $this->sqlFileOrder;
        $_SESSION['filequery_pos'] = $this->fileOffset;
    }
    
    public function handlePixOptions(){
        if (isset($_GET['filter'])) {
            $this->pixFilter = $_GET['filter'];
            setcookie('pixfilter', $this->pixFilter, time() + COOKIE_LIFETIME);
            $this->setSQLPixFilter();
        } else {
            //$this->pixFilter = "";
            //setcookie('pixfilter', $this->pixFilter, time() + COOKIE_LIFETIME);
            //$this->sqlPixFilter = "";
        }
        
        if (isset($_GET['style'])) {
            $this->pixStyle = $_GET['style'];
            setcookie('pixstyle', $this->pixStyle, time() + COOKIE_LIFETIME);
            // TODO: check compatibility $this->page = 1;
        } //else
        
        if (isset($_GET['order'])) {
            $this->pixOrder = $_GET['order'];
            setcookie('pixorder', $this->pixOrder, time() + COOKIE_LIFETIME);
            $this->setSQLPixOrder();
        }
        
        // todo: set PageSize according to style
        
        if (isset($_GET['page'])) {
            $page = $_GET['page'] - 1;
            $this->pixOffset = $page * $this->pixPageSize;
        } else
            $this->pixOffset = 0;
            
            $_SESSION['pixquery_filter'] = $this->sqlPixFilter;
            $_SESSION['pixquery_order'] = $this->sqlPixOrder;
            $_SESSION['pixquery_pos'] = $this->pixOffset;
    }
    
    private function setSQLFileFilter(){
        switch ($this->fileFilter) {
            case 'Videos':
                $this->sqlFileFilter = " REF_FileType = 3";
                break;
            case 'Pictures':
                $this->sqlFileFilter = " REF_FileType = 2";
                break;
            case 'OtherFiles':
                $this->sqlFileFilter = " REF_FileType <> 3 AND REF_FileType <> 2 ";
                break;
            case 'HQHDVideos':
                $this->sqlFileFilter = " REF_FileType = 3 AND ResY > 500";
                break;
            case 'Top':
                $this->sqlFileFilter = " Rating = 5 ";
                break;
            case 'Unrated':
                $this->sqlFileFilter = " Rating IS NULL ";
                break;
            case 'All':
            default:
                $this->sqlFileFilter = "";
                break;
        }
    } 
    
    private function setSQLFileOrder(){
        if ($this->fileOrder == 'Name')
            $this->sqlFileOrder = 'REF_Device, Path, Name';
        else if ($this->fileOrder == 'Name DESC') {
            $this->sqlFileOrder = 'REF_Device DESC, Path DESC, Name DESC';
        } else {
                $this->sqlFileOrder = $this->fileOrder;
        }
    }
    
    private function setSQLPixFilter(){
        switch ($this->pixFilter) {
            case 'Pictures':
                $this->sqlPixFilter = " REF_Filetype = 2";
                break;
            case 'OtherPixs':
                $this->sqlPixFilter = " REF_Filetype <> 3 AND REF_Filetype <> 2 ";
                break;
            case 'All':
            default:
                $this->sqlPixFilter = "";
                break;
        }
    }
    
    private function setSQLPixOrder(){
        if ($this->pixOrder == 'Name')
            $this->sqlPixOrder = 'REF_Device, Path, Name';
            else if ($this->pixOrder == 'Name DESC') {
                $this->sqlPixOrder = 'REF_Device DESC, Path DESC, Name DESC';
            } else {
                $this->sqlPixOrder = $this->pixOrder;
            }
    }
    
    abstract public function showfiles();
}

