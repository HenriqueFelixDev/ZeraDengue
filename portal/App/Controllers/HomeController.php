<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        global $twig;
        echo $twig->render("home/home.php");
    }
}