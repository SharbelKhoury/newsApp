<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        $newsData = $this->fetchNewsData();

        return $this->render('dashboard/index.html.twig', [
            'newsData' => $newsData,
        ]);
    }

    private function fetchNewsData(): array
    {
        $apikey = '901b918d581dfcd1726864f92a539b1d'; // Your GNews API key
        $url = "https://gnews.io/api/v4/search?q=latest&lang=ar&country=us&max=10&apikey=$apikey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $articles = $data['articles'] ?? [];
        $processedData = [
            'latest' => [], // Use this for the latest news items
            'politics' => [], // Placeholder for other categories
            'economy' => [],
            'security' => [],
        ];

        // Process each article
        foreach ($articles as $item) {
            $newsItem = [
                'title' => $item['title'] ?? 'No Title',
                'image' => $item['image'] ?? '', // Assuming the GNews API returns 'image'
                'timestamp' => date('Y-m-d H:i:s'), // You can format this as needed
                'url' => $item['url'] ?? '#', // Adding URL for article links
            ];

            // Add to 'latest' news section (limit to 10 items as per your query)
            if (count($processedData['latest']) < 10) {
                $processedData['latest'][] = $newsItem;
            }
        }

        return $processedData;
    }

    // Optionally implement a function to format timestamps
    private function formatTimestamp(string $date): string
    {
        return date('Y-m-d H:i:s', strtotime($date)); // Customize as needed
    }
}
