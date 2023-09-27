<?php

namespace App\Service;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorManager
{

    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function updateAuthor(Request $request, ?int $id = null): ?FormInterface
    {
        if($id === null){
            $author = new Author();
        } else {
            $repository = $this->entityManager->getRepository(Author::class);
            $author = $repository->find($id);
        }
        $form = $this->formFactory->create(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            return $form;
        }

        return $form;
    }

    public function getAuthor(int $id): ?Author
    {
        return $this->entityManager->getRepository(Author::class)->find($id);
    }

    public function deleteAuthor(int $id): void
    {
        $repository = $this->entityManager->getRepository(Author::class);
        $author = $repository->find($id);
        $this->entityManager->remove($author);
        $this->entityManager->flush();
    }
}