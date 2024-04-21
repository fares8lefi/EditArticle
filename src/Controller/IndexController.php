<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use PhpParser\Node\Expr\New_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class IndexController extends AbstractController
{
    /**
     * @Route("/",name="article_list")
     */
    public function home()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article\index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $Article = new article();
        $Article->setNom('article3');
        $Article->setPrix(1700);

        $entityManager->persist($Article);
        $entityManager->flush();

        return new Response('un nouveaux article enregistré sous ID=' . $Article->getId());
    }


    /**
     * @Route("/article/new",name="new_article")
     * @Method({"GET","POST"})
     */
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articel = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }
        return $this->render('Article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/article/edit/{id}",name="edit_article")
     * @Method({"GET","POST"})
     */
    public function edit(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager ->getRepository(Article::class)->find($id);
        if(!$article){
         throw $this->createNotFoundException('article non tropuvé avec l\'identifient '. $id);
        }
         $form = $this-> createForm(ArticleType  ::class ,$article);
         $form ->handleRequest($request) ;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }
        return $this->render('article/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/delete/{id}")
     * @Method({"GET","POST"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new response();
        $response->send();

        return $this->redirectToRoute('article_list');
    }
    /**
     * @Route("/article/{id}",name="article_show")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/show.html.twig', ['article' => $article]);
    }
    /**
     * @Route("/category/NewCat",name="new_category")
     * @Method({"GET","POST"})
     */
    public function NewCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }
        return $this->render('Article/newCat.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
