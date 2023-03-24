<?php

namespace App\Controller;

use App\Service\AuthorManager;
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

#[Autoconfigure(calls: [['setAuthorManager' => ['@manager.author']]])]
class AuthorController extends AbstractController
{

    private AuthorManager $authorManager;

    public function setAuthorManager(AuthorManager $authorManager)
    {
        $this->authorManager = $authorManager;
    }

    #[Route('/author', name: 'add_author')]
    public function add(Request $request): Response
    {
        $form = $this->authorManager->updateAuthor($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Success');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('author/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/author/{id}', name: 'show_author')]
    public function show(int $id): Response
    {
        $author = $this->authorManager->getAuthor($id);
        return $this->render('author/show.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/author/edit/{id}', name: 'edit_author')]
    public function edit(Request $request, $id): Response
    {
        $form = $this->authorManager->updateAuthor($request, $id);
        if ($form->isSubmitted() &&  $form->isValid()) {
            $this->addFlash('success', 'Success');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('author/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'delete_author')]
    public function delete(int $id): Response
    {
        $this->authorManager->deleteAuthor($id);

        $this->addFlash('success', 'Success delete');
        return $this->redirectToRoute('app_main');
    }


}
