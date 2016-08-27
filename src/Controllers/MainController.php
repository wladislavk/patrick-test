<?php
namespace Controllers;

use Components\Response;

class MainController extends BaseController
{
    public function index()
    {
        $viewData = [];
        return new Response(indexTemplate($viewData));
    }
}
