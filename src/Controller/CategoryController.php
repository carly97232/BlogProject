<?php
/**
 * Created by PhpStorm.
 * User: jovanela
 * Date: 08/11/18
 * Time: 16:22
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/desc/{category}", name="blog_show_category")
     * @param string $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByCategory(string $category): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findOneBy(['name'=>$category]);
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findBy(
            ['category'=> $category],
            ['id'=>'DESC'],
            3
        );
        return $this->render(
            'blog/category2.html.twig',
            ['category' => $category, 'articles'=> $articles]
        );
    }

    /**
     * Show all row from category's entity
     *
     * @Route("/category", name="blog_category_index")
     * @return Response A response instance
     */
    public function index(Request $request) : Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$category) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }
        $form = $this->createForm(
            CategoryType::class,
            null,
            ['method' => Request::METHOD_POST]
        );
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $this -> redirectToRoute ( 'blog_category_index' );
        }
        return $this->render(
            'blog/category.html.twig', [
                'categories' => $category,
                'form' => $form->createView(),
            ]
        );
    }
}