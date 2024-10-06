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
        $newsItem = new News();
        $form = $this->createForm(NewsType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($newsItem);
            $this->entityManager->flush();

            return $this->json($newsItem, Response::HTTP_CREATED);
        }

        return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
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
