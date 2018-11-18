<?php
/**
 * Created by PhpStorm.
 * User: jovanela
 * Date: 18/11/18
 * Time: 15:48
 */
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/article/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @Route("blog/category/{category}", name="category_showAllByCategory")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllByCategory($title): Response
    {
        $category = new Category();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findOneBy($title);
        $articles = $category->getArticles();

        return $this->render('blog/category.html.twig', ['categories' => $category, 'articles' => $articles]);
    }

    /**
     * Show all row from article's entity
     *
     * @Route("/articles", name="blog_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }
    /**
     * @Route("/category/{category}", name="blog_show_category")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByCategory(string $category): Response
    {
        $category = new Category();
        $category = $this->getDoctrine()
            ->getRepository(Category::class);
        $articles = $category->getArticles()
            ->findBy(
            ['articles'=> $articles],
            ['id'=>'DESC'],
            3
        );
        return $this->render(
            'blog/category.html.twig',
            ['category' => $category, 'articles'=> $articles]
        );
    }
}