<?php
/**
 * Created by PhpStorm.
 * User: jovanela
 * Date: 18/11/18
 * Time: 15:48
 */
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{slug}", requirements={"slug"="[a-z{0-9}-]+"}, name="blog_show")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($slug='Article Sans Titre')
    {
        $slug = ucwords(str_replace("-", " ", $slug));
        return $this->render('slug.html.twig', ['slug' => $slug]);
    }
}