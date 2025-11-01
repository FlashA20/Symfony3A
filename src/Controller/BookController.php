<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/book/list', name: 'book_list')]
    public function listBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);

    }
    #[Route('/book/add', name: 'book_add')]
    public function addBook(ManagerRegistry $doctrine, Request $request): Response{
        $book= new Book();
        $f=$this->createForm(BookType::class,$book);
        $f->handleRequest($request);
        if($f->isSubmitted()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('book/add.html.twig', [
            'form' => $f->createView(),
        ]);
    }
    #[Route('/book/delete/{id}', name: 'book_delete')]
    public function deleteBook(ManagerRegistry $doctrine, BookRepository $bookRepository, $id): Response{
        $entityManager = $doctrine->getManager();
        $book = $bookRepository->find($id);
        if ($book) {
            $entityManager->remove($book);
        }
        return $this->redirectToRoute('book_list');
    }
}