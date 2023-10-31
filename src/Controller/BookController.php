<?php

namespace App\Controller;
use App\Entity\Author;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


    /**
     * @Route("/addB", name="addB")
     */

    public function add(Request $request){

$book=new Book();
$book->setPublished(true);
$form=$this->createForm(BookType::class,$book);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) { 
    $author = $book->getAuthor();
    if($author instanceof author ){    $author->setNbBooks($author->getNbBooks() + 1);
    }
         $em=$this->getDoctrine()->getManager();
         $em->persist($author);
         $em->persist($book);
          $em->flush();

          return $this->redirectToRoute('listB');
}
return $this->render('book/add.html.twig' ,   [
    'form'=>$form->createView(),
]);
    }


    

 /**
     * @Route("/listB", name="listB")
     */
    public function list(BookRepository $em){
        $b=$em->findBy(['published'=>true]);
        $nonpublished=$em->findBy(['published'=>false]);
        $books = $em->booksListByAuthors();
        return $this->render('book/list.html.twig'   ,[
        'b'=>$b,
        'bo'=>$books,
        'non'=>$nonpublished,
        ])   ;

    }



/**
     * @Route("/updateB/{id}", name="updateB")
     */

     public function update(BookRepository $em ,Request $request,$id ){
        $author=$em->find($id);
        $form=$this->createForm(BookType::class, $author);
     $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em=$this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('listB');
        }
         return $this->render('book/update.html.twig'   ,[
            'form'=>$form->createView(),

         ]);




        }

        /**
         * @Route("/deleteB/{id}", name="deleteB")
         */
    public function delete($id,BookRepository $repo)
    {
    $data=$repo->find($id);
    $em=$this->getDoctrine()->getManager();
    $em->remove($data);
    $em->flush();
    return $this->redirectToRoute(('listB'));
    }
  /**
         * @Route("/show/{id}", name="show")
         */

     public function show(BookRepository $em,$id){
        $book=$em->find($id);

        return$this->render('book/show.html.twig'  ,[
            'book'=>$book,
        ]);

     }

     public function searchBook(Request $request, BookRepository $bookRepository): Response
     {
         $id = $request->query->get('id');
         $book = null;
 
         if ($id) {
             $book = $bookRepository->searchBookByRef($id);
         }
 
         return $this->render('book/list.html.twig', [
             'b' => $book,
         ]);
     }
      /**
     * @Route("/search", name="search")
     */

     public function dql(EntityManagerInterface $em, Request $request,BookRepository $st){
        $req=$em->createQuery("select s from App\Entity\Book s where s.name=:n ");
  $result=$st->findAll();

if($request->isMethod('post')){
 $value=$request->get('test');

$req->setParameter('n',$value);
$result=$req->getResult();
}

return $this->render('book/search.html.twig',[
    'form'=>$result,
]);

}
}
