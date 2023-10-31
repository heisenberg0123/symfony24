<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    
     /**
     * @Route("/list", name="list")
     */
    public function list(AuthorRepository $em){
        $author=$em->findall();
        $au=$em->listAuthorByEmail();
        return $this->render('author/list.html.twig'   ,[
        'author'=>$author,
       'au'=>$au,
        ])   ;

    }

/**
     * @Route("/add", name="adds")
     */

     public function add(Request $request)
     {
         $student= new Author();
         $form= $this->createForm(AuthorType::class, $student);
         $form->handleRequest($request);
 
 
         if($form->isSubmitted() && $form->isValid()){
             $em=$this->getDoctrine()->getManager();
             $em->persist($student);
             $em->flush();
 
             return $this->redirectToRoute('list');
         }
 
         return $this->render('author/add.html.twig'   ,[
             'form'=>$form->createView(),
         ]);
 
     }

/**
     * @Route("/update/{id}", name="update")
     */
     public function update(AuthorRepository $em ,Request $request,$id ){
        $author=$em->find($id);
        $form=$this->createForm(AuthorType::class, $author);
     $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em=$this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('list');
        }
         return $this->render('author/update.html.twig'   ,[
            'form'=>$form->createView(),

         ]);




     }


    
    /**
         * @Route("/delete/{id}", name="delete")
         */
    public function delete($id,AuthorRepository $repo)
    {
    $data=$repo->find($id);
    $em=$this->getDoctrine()->getManager();
    $em->remove($data);
    $em->flush();
    return $this->redirectToRoute(('list'));
    }



    
}
