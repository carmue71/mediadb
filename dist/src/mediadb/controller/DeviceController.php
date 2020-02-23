<?php

/* DeviceController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Controls the frontend interaction for devices
 */

namespace mediadb\controller;

use mediadb\model\Device;

class DeviceController extends AbstractController
{

    public function __construct($rep)
    {
        parent::__construct($rep);
        $this->currentSection = "Devices";
        // TODO - Insert your code here
    }

    public function showAll()
    {
        $this->updatePageNumbers();
        $this->render("list", ['entries' => $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize,"","Name") ]);
    }

    public function show(String $title = "")
    {
        $this->render("show", ['entry' => $this->repository->find($_GET['id'])]);
    }

    protected function render($view, $params)
    {
        $this->currentView = "";
        switch ( $view ){
            case 'edit':
                $device = $params['entry'];
                $this->pageTitle = "Edit your device";
                include VIEWPATH."device/edit_device.php";
                break;
                
            case 'list':
                if ( isset($params['entries']) ){
                    $devices = $params['entries'];
                    $this->pageTitle = "List of your devices";
                    include VIEWPATH.'device/list_devices.php';
                }
                break;
                
            case 'scan':
                $device = $params['entry'];
                $this->pageTitle = "Scanning your device";
                include VIEWPATH."device/scan_device.php";
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
        $this->render("scan", ['entry' => $this->repository->find($id)]);
    }
    
    public function add()
    {
        $device = new Device();
        $device->ID_Device = -1;
        $this->render("edit", ['entry' => $device]);
    }
    
    public function edit()
    {
        $id = $_GET['id'];
        $this->render("edit", ['entry' => $this->repository->find($id)]);
    }
    
    public function save()
    {
        $device = new Device();
        $device->Name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES);
        $device->Path = htmlspecialchars(trim($_POST['path']), ENT_QUOTES);
        if ( substr($device->Path, -1) != DIRECTORY_SEPARATOR )
            $device->Path = $device->Path.DIRECTORY_SEPARATOR;
        $device->DisplayPath = htmlspecialchars(trim($_POST['dpath']), ENT_QUOTES);
        if ( substr($device->DisplayPath, -1) != DIRECTORY_SEPARATOR )
            $device->DisplayPath = $device->DisplayPath.DIRECTORY_SEPARATOR;
        $device->Comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES);
        $device->ID_Device = $_POST['id'];
        
        $this->repository->save($device);
                
        $this->showAll();
    }
    
    
    public function delete()
    {}
}

