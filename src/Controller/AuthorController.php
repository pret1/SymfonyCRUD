<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;

//#[Autoconfigure(calls: ['setMyClass' => '@doctrine'])]
class AuthorController extends AbstractController
{
//    private $doctrine;
//    public function setMyClass(ManagerRegistry $doctrine)
//    {
//        $this->doctrine = $doctrine;
//    }

    #[Route('/author', name: 'app_author')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $author->setName('Tolkien');
        $author->setAge(43);
        $author->setGenre('Fantasy');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($author);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$author->getId());
//        return $this->render('author/index.html.twig', [
//            'controller_name' => 'AuthorController',
//        ]);
    }
}
