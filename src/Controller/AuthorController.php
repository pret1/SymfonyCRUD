<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'add_author')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Success');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('author/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/author/{id}', name: 'show_author')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(Author::class);
        $author = $repository->find($id);

        return $this->render('author/show.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/author/edit/{id}', name: 'edit_author')]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $repository = $entityManager->getRepository(Author::class);
        $author = $repository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Success');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('author/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'delete_author')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(Author::class);
        $author = $repository->find($id);
        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'Success delete');
        return $this->redirectToRoute('app_main');
    }


}
