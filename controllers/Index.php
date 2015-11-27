<?php namespace Genius\Base\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Lang;

/**
 * Index Back-end Controller
 */
class Index extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function pages()
    {

        BackendMenu::setContext('Genius.Base', 'pages');
        $this->pageTitle = 'genius.base::lang.menu.pages';
    }

    public function admin()
    {

        BackendMenu::setContext('Genius.Base', 'admin');
        $this->pageTitle = 'genius.base::lang.menu.admin';
    }

    public function models()
    {

        BackendMenu::setContext('Genius.Base', 'models');
        $this->pageTitle = 'genius.base::lang.menu.models';
    }
}