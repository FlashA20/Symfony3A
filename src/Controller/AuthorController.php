<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;

final class AuthorController extends AbstractController
{
    #[Route('/author/list', name: 'author_list')]
    public function listAuthors(): Response
    {
        $authors =array (
            array('id' => '1', 'picture'=>'/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com','nb_books'=>'100'),
            array('id' => '2', 'picture'=>'/images/William-Shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com','nb_books'=>'200'),
            array('id' => '3', 'picture'=>'/images/TahaHussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com','nb_books'=>'300')
        );

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }
    #[Route('author/getAll', name: 'getAll')]
    public function getAllAuthors(AuthorRepository $auth): Response
    {
        $authors = $auth->findAll();
        return $this->render('author/listup.html.twig', [
            'authors' => $authors,
        ]);
    }
    #[Route ('/author/ajout', name: 'ajout')]
    public function ajoutAuthor(ManagerRegistry $doctrine, Request $request): Response
    {
        $author = new Author();
        $form= $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
        if($form->isSubmitted()){
        $entityManager = $doctrine->getManager();
        $entityManager->persist($author);
        $entityManager->flush();
        return $this->redirectToRoute('getAll');
    }

        return $this->render('author/ajout.html.twig'
        ,['form'=>$form->createView()]);
    }
    #[Route('/author/update/{id}', name: 'update')]
    public function updateAuthor(ManagerRegistry $doctrine,AuthorRepository $auth,$id): Response{
        $author = $auth->find($id);
        $em= $doctrine->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('getAll');
    }
    #[Route('/author/delete/{id}', name: 'delete')]
    public function deleteAuthor(ManagerRegistry $doctrine, AuthorRepository $auth, $id): Response
    {
        $author = $auth->find($id);
        if ($author) {
            $em = $doctrine->getManager();
            $em->remove($author);
            $em->flush();
        }
        return $this->redirectToRoute('getAll');
    }
    #[Route('/author/{id}', name: 'author_details')]
    public function showAuthorById($id): Response
    {
        $authors =array (
            array('id' => '1', 'picture'=>'/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com','nb_books'=>'100'),
            array('id' => '2', 'picture'=>'/images/William-Shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com','nb_books'=>'200'),
            array('id' => '3', 'picture'=>'/images/TahaHussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com','nb_books'=>'300')
        );
        return $this->render('author/showAuthor.html.twig', [
            'id' => $authors[$id],
        ]);

    }
    #[Route('/author/{name}', name: 'app_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }
}
