<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NewsRepository;
use App\Repository\UserRepository;
use App\Repository\LanguageRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;

class ApiController extends AbstractController
{
    private $newsRepository;
    private $usersRepository;
    private $languagesRepository;
    private $categoriesRepository;
    private $tagsRepository;


    public function __construct(NewsRepository $newsRepository, UserRepository $usersRepository, LanguageRepository $languagesRepository, CategoryRepository $categoriesRepository, TagRepository $tagsRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->usersRepository = $usersRepository;
        $this->languagesRepository = $languagesRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
    }
    /* NEWS */
    #[Route('/api/news', name: 'api_news', methods: ['GET'])]
    public function getAllNews(): JsonResponse
    {
        $news = $this->newsRepository->findAll();

        return $this->json($news);
    }

    #[Route('/api/news/{id}', name: 'api_news_show', methods: ['GET'])]
    public function getNews($id): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return $this->json(['message' => 'News not found'], 404);
        }

        return $this->json($news);
    }

    /* USERS */
    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->usersRepository->findAll();

        return $this->json($users, 200, [], ['groups' => ['user:read']]);
    }

    #[Route('/api/users/{id}', name: 'api_users_show', methods: ['GET'])]
    public function getUsers($id): JsonResponse
    {
        $users = $this->usersRepository->find($id);

        if (!$users) {
            return $this->json(['message' => 'users not found'], 404);
        }

        return $this->json($users, 200, [], ['groups' => ['user:read']]);
    }

    /* Languages */
    #[Route('/api/languages', name: 'api_languages', methods: ['GET'])]
    public function getAllLanguages(): JsonResponse
    {
        $languages = $this->languagesRepository->findAll();

        return $this->json($languages, 200, [], ['groups' => ['language:read']]);
    }

    #[Route('/api/languages/{id}', name: 'api_languages_show', methods: ['GET'])]
    public function getLanguages($id): JsonResponse
    {
        $languages = $this->languagesRepository->find($id);

        if (!$languages) {
            return $this->json(['message' => 'languages not found'], 404);
        }

        return $this->json($languages, 200, [], ['groups' => ['language:read']]);
    }
    /* Categories */
    #[Route('/api/categories', name: 'api_categories', methods: ['GET'])]
    public function getAllCategories(): JsonResponse
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->json($categories, 200, [], ['groups' => ['category:read']]);
    }

    #[Route('/api/categories/{id}', name: 'api_categories_show', methods: ['GET'])]
    public function getCategories($id): JsonResponse
    {
        $categories = $this->categoriesRepository->find($id);

        if (!$categories) {
            return $this->json(['message' => 'categories not found'], 404);
        }

        return $this->json($categories, 200, [], ['groups' => ['category:read']]);
    }

    /* Tags */
    #[Route('/api/tags', name: 'api_tags', methods: ['GET'])]
    public function getAllTags(): JsonResponse
    {
        $tags = $this->tagsRepository->findAll();

        return $this->json($tags, 200, [], ['groups' => ['tag:read']]);
    }

    #[Route('/api/tags/{id}', name: 'api_tags_show', methods: ['GET'])]
    public function getTags($id): JsonResponse
    {
        $tags = $this->tagsRepository->find($id);

        if (!$tags) {
            return $this->json(['message' => 'Tags not found'], 404);
        }

        return $this->json($tags, 200, [], ['groups' => ['tag:read']]);
    }
}
