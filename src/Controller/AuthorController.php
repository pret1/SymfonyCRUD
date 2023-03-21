<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;

//#[Autoconfigure(calls: ['setMyClass' => '@doctrine'])]
class AuthorController extends AbstractController
{
//    private $doctrine;
//    public function setMyClass(ManagerRegistry $doctrine)
//    {
//        $this->doctrine = $doctrine;
//    }

    #[Route('/author', name: 'add_author')]
    public function index(EntityManagerInterface $entityManager): Response
    {

//        $author = new Author();
//        $author->setName('Tolkien');
//        $author->setAge(43);
//        $author->setGenre('Fantasy');
//
//        // tell Doctrine you want to (eventually) save the Product (no queries yet)
//        $entityManager->persist($author);
//
//        // actually executes the queries (i.e. the INSERT query)
//        $entityManager->flush();
//
//        return $this->render('author/index.html.twig', [
//            'controller_name' => 'AuthorController',
//        ]);
    }

    #[Route('/author/all', name: 'app_author')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Author::class);
        $author = $repository->findAll();

        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $author
        ]);
    }
}
