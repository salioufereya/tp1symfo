<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
use App\Form\CategoryType;
class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'name'=>'dillo',
        ]);
    }
    /**
     * @Route("/category/newCat",name="new_category")
     * Method({"GET","POST})
     */

     public function newCategory(Request $request,ManagerRegistry $doctrine){
         $category= new Category();
         $form= $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager=$doctrine->getManager();  
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/newCategory.html.twig',
        ['form'=>$form->createView()]);
     }

}
