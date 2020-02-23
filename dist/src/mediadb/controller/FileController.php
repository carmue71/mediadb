<?php

/* FileController.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Implements the FileBaseController for files
 */

namespace mediadb\controller;

class FileController extends AbstractController
{

    public function __construct($rep)
    {
        parent::__construct($rep);        
    }

    public function showAll()
    {
        $this->updatePageNumbers();
        $this->renderPage("listfiles", ['entries' => $this->repository->getAll($this->pageSize, ($this->page - 1) * $this->pageSize) ]);
    }

    public function show($title = "")
    {
        $id = $_GET['id'];
        $this->renderPage("showfile", ['entry' => $this->repository->find($id)], $title);
    }

    protected function render($view, $params)
    {
    }
}

