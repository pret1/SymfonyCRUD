<?php

namespace App\Service;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class AuthorManager extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->logger = $logger;
    }

    public function updateAuthor(Request $request, SluggerInterface $slugger, ?int $id = null): ?FormInterface
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
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->logger->error('An error occurred in updateAuthor ' . $e->getMessage());
                }

                $author->setImage($newFilename);
            }
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