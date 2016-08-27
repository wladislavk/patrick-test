<?php
namespace Controllers;

class MainController extends BaseController
{
    public function index()
    {
        $viewData = [];
        return indexTemplate($viewData);
    }
}
