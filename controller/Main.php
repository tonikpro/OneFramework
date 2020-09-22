<?php 
namespace controller;
use etc\app\Controller;
use etc\app\App;
use model\Auth;
use model\PersonSearch;

class Main extends Controller
{
    public function actionMain()
    {
        return $this->render('main/index');
    }
}