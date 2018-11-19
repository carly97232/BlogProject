<?php
/**
 * Created by PhpStorm.
 * User: jovanela
 * Date: 08/11/18
 * Time: 16:22
 */

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{name}", name="category_show")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Category $category) :Response
    {
        return $this->render('category.html.twig', ['category'=>$category]);
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