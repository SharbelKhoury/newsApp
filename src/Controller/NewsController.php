<?php
// src/Controller/NewsController.php
// src/Controller/NewsController.php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/api/news')]
class NewsControllerAPI extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'news_index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        $newsItems = $newsRepository->findAll();

        return $this->json($newsItems);
    }

    #[Route('/add', name: 'news_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        // Create a new News entity instance
        $news = new News();

        // Create the form
        $form = $this->createForm(NewsType::class, $news);

        // Handle the request
        $form->handleRequest($request);

        // Debug: Check if form is submitted
        dump('Form submitted: ', $form->isSubmitted());

        // Debug: Check if form is valid
        dump('Form valid: ', $form->isValid());

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image uploads
            $images = $form->get('images')->getData();

            // Initialize an array to hold image paths
            $imagePaths = [];

            if ($images) {
                foreach ($images as $image) {
                    // Get the original file name and generate a unique filename
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = uniqid() . '.' . $image->guessExtension();

                    // Move the file to the directory where images are stored
                    try {
                        $image->move(
                            $this->getParameter('images_directory'), // Set this parameter in services.yaml
                            $newFilename
                        );
                        // Add the new filename to the image paths array
                        $imagePaths[] = $newFilename;
                    } catch (FileException $e) {
                        // Handle exception if something happens during file upload
                        dump('File upload error: ', $e->getMessage());
                    }
                }
                // Set the images in the News entity (assuming images is a string array)
                $news->setImages($imagePaths);
            }

            // Persist the news entity
            $this->entityManager->persist($news);
            $this->entityManager->flush();

            // Debug: Log success message
            dump('News item saved successfully.');

            // Redirect response
            return $this->json(['redirect' => $this->generateUrl('news_index')]);
        }

        // Debug: Log form errors if the form is not valid
        dump('Form errors:', $form->getErrors(true, false));

        // Render the form view in case of error or invalid submission
        return $this->render('news/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'news_edit', methods: ['PUT'])]
    public function edit(Request $request, News $newsItem): Response
    {
        $form = $this->createForm(NewsType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->json($newsItem);
        }

        return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'news_delete', methods: ['DELETE'])]
    public function delete(News $newsItem): Response
    {
        $this->entityManager->remove($newsItem);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
#[Route('/news')]
class NewsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'news_index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        $newsItems = $newsRepository->findAll();
        return $this->render('news/index.html.twig', [
            'news' => $newsItems,
        ]);
    }

    #[Route('/add', name: 'news_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $newsItem = new News();
        $form = $this->createForm(NewsType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($newsItem);
            $this->entityManager->flush();
            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $newsItem): Response
    {
        $form = $this->createForm(NewsType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/edit.html.twig', [
            'form' => $form->createView(),
            'news' => $newsItem,
        ]);
    }

    #[Route('/{id}/delete', name: 'news_delete', methods: ['POST'])]
    public function delete(News $newsItem): Response
    {
        $this->entityManager->remove($newsItem);
        $this->entityManager->flush();
        return $this->redirectToRoute('news_index');
    }
}
