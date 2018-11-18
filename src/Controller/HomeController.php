<?php
/**
 * Created by PhpStorm.
 * User: jovanela
 * Date: 18/11/18
 * Time: 14:01
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name = "Hello")
     */
    public function index()
    {
        return $this->render('home.html.twig');
    }
}
