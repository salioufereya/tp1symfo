<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping\Id;
use App\Form\UserType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\Article;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ContainerArcCtMJ\PaginatorInterface_82dac15;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')] 
    public function index(ManagerRegistry $doctrine,Request $request,PaginatorInterface $paginator): Response
    {
          
      $propertySearch= new PropertySearch();
      $form= $this->createForm(PropertySearchType::class,$propertySearch);
      $form->handleRequest($request);
      $entityManager=$doctrine->getManager();
      $users=$entityManager->getRepository(User::class)->findAll();
      $users=[];
      $nom=$propertySearch->getNom();
      if($nom== null){
        $users=$entityManager->getRepository(User::class)->findAll();
      }
    else if($form->isSubmitted() && $form->isValid()){
          $nom=$propertySearch->getNom();
          $entityManager=$doctrine->getManager();
          if($nom!="")
          $users= $entityManager->getRepository(User::class)->findBy(['nom'=>$nom]);
          else
          $users=$entityManager->getRepository(User::class)->findAll();
          
      }
      $userp=$paginator->paginate(
       $users, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        3/*limit per page*/
    );
        return $this->render('user/index.html.twig',
        ['form'=>$form->createView(),'users'=>$userp]
    );
    }
    #[Route('/user/save', name: 'app_user_save')]
    public function save(ManagerRegistry $doctrine): Response
    {
     
        $entityManager=$doctrine->getManager();  
        $user= new User();
        $user->setNom('Fall');
        $user->setPrenom('Aliou');
        $user->setEmail('alioufereya19@gmail.com');
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response (' User ajouté avec l id ' .$user->getId());
    }


   
   

   
    
    #[Route('/user/new', name: 'new_user')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
        
        // $user = new User();
        // $form = $this->createFormBuilder($user)
        //     ->add('nom', TextType::class)
        //     ->add('prenom', TextType::class)
        //     ->add('email', TextType::class) 
        //     ->add('save', SubmitType::class, ['label' => 'Creer un utilisateur'])
        //     ->getForm();
        // //  $user = new User();   
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $user = $form->getData();
        //     $entityManager=$doctrine->getManager();  
        //     $entityManager->persist($user);
        //     $entityManager->flush();
        //     return $this->redirectToRoute('app_user');
        //  }
        //  return $this->render('user/neew.html.twig',
        //  ['form'=>$form->createView()]);
        $user=new User();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager=$doctrine->getManager();  
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/neew.html.twig',
        ['form'=>$form->createView()]);

}

#[Route('/user/{id}', name: 'app_user_show')]
    public function show(ManagerRegistry $doctrine,$id): Response
    {
        $entityManager=$doctrine->getManager();  
        $user= $entityManager->getRepository(User::class)->find($id);
        return $this->render('user/show.html.twig',
        ['user'=>$user]
    );
    }


    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    /**
     *Method({"GET,"POST"})
     */
    
    
        public function edit(Request $request,ManagerRegistry $doctrine, $id): Response
        {
            // creates a task object and initializes some data for this example
            // $user = new User();
            // $entityManager=$doctrine->getManager();  
            // $user= $entityManager->getRepository(User::class)->find($id);
            // $form = $this->createFormBuilder($user)
            //     ->add('nom', TextType::class)
            //     ->add('prenom', TextType::class)
            //     ->add('email', TextType::class)
            //     ->add('save', SubmitType::class, ['label' => 'Modifier'])
            //     ->getForm();
            //     $user = new User();
               
            // $form->handleRequest($request);
            // if ($form->isSubmitted() && $form->isValid()) {
            //     // $form->getData() holds the submitted values
            //     // but, the original `$task` variable has also been updated
            //     $user = $form->getData();
            //     $entityManager=$doctrine->getManager();  
            //    // $user= new User();
            //     $entityManager->persist($user);
            //     $entityManager->flush();
            //     return $this->redirectToRoute('app_user');
    
            //  }
            //  return $this->render('user/edit.html.twig',
            //  ['form'=>$form->createView()]);
        $user=new User();
        $entityManager=$doctrine->getManager();
        $user= $entityManager->getRepository(User::class)->find($id);
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager=$doctrine->getManager();  
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
         return $this->render('user/edit.html.twig',
             ['form'=>$form->createView()]);



    
    }

    #[Route('/user/delete/{id}', name: 'app_user_new')]
    /**
     *Method({"GET,"POST"})
     */
    
    
        public function delete(Request $request,ManagerRegistry $doctrine, $id): Response
        {
            // creates a task object and initializes some data for this example
            $entityManager=$doctrine->getManager();  
            $user= $entityManager->getRepository(User::class)->find($id);
            $entityManager=$doctrine->getManager(); 
            $entityManager->remove($user); 
            $entityManager->flush();
            $response= new Response();
            $response->send();
             return $this->redirectToRoute('app_user');
            
    }



}