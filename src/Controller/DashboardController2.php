<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController2 extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        $newsData = $this->fetchNewsData();

        if (empty($newsData)) {
            // Log the error or add flash message
            $this->addFlash('error', 'Unable to fetch live news data. Displaying mock data instead.');
            $newsData = $this->getMockData(); // Provide fallback mock data in case of failure
        }

        return $this->render('dashboard/index.html.twig', [
            'newsData' => $newsData,
        ]);
    }

    private function getMockData(): array
    {
        return [
            'latest' => [
                ['title' => 'عنوان الخبر الأول', 'timestamp' => 'منذ 5 دقائق'],
                ['title' => 'عنوان الخبر الثاني', 'timestamp' => 'منذ 10 دقائق'],
                ['title' => 'عنوان الخبر الثالث', 'timestamp' => 'منذ 15 دقيقة'],
            ],
            'politics' => [
                ['title' => 'خبر سياسي 1', 'image' => 'https://via.placeholder.com/300x200.png?text=Politics+1', 'timestamp' => 'منذ ساعة'],
                ['title' => 'خبر سياسي 2', 'image' => 'https://via.placeholder.com/300x200.png?text=Politics+2', 'timestamp' => 'منذ ساعتين'],
            ],
            'economy' => [
                ['title' => 'خبر اقتصادي 1', 'image' => 'https://via.placeholder.com/300x200.png?text=Economy+1', 'timestamp' => 'منذ 3 ساعات'],
                ['title' => 'خبر اقتصادي 2', 'image' => 'https://via.placeholder.com/300x200.png?text=Economy+2', 'timestamp' => 'منذ 4 ساعات'],
            ],
            'security' => [
                ['title' => 'خبر أمني 1', 'image' => 'https://via.placeholder.com/300x200.png?text=Security+1', 'timestamp' => 'منذ 5 ساعات'],
                ['title' => 'خبر أمني 2', 'image' => 'https://via.placeholder.com/300x200.png?text=Security+2', 'timestamp' => 'منذ 6 ساعات'],
            ],
        ];
    }

    private function fetchNewsData(): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://arabic-news-api.p.rapidapi.com/bbcarabic",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: arabic-news-api.p.rapidapi.com",
                "x-rapidapi-key: 31ecb61549msh324a427b50a390fp15675fjsn866898cfadb4" // Replace with your actual API key
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return []; // Return empty array on error
        } else {
            $data = json_decode($response, true); // Decode the JSON response to an array

            // Initialize an empty structure to hold categorized news data
            $processedData = [
                'latest' => [],
                'politics' => [],
                'economy' => [],
                'security' => [],
            ];

            foreach ($data as $item) {
                // For each news item, create an array with title, image, and timestamp
                $newsItem = [
                    'title' => $item['title'],
                    'image' => $item['image'] ?? '',  // Use image if available, else empty string
                    'url' => $item['url'] ?? '#',  // Add URL field for news links
                ];

                // Check if 'date' exists and format it
                if (isset($item['date'])) {
                    $newsItem['timestamp'] = $this->formatTimestamp($item['date']);
                } else {
                    $newsItem['timestamp'] = 'تاريخ غير متوفر'; // Fallback message if date is missing
                }

                // Add to 'latest' news section (limit to 5 items)
                if (count($processedData['latest']) < 10) {
                    $processedData['latest'][] = $newsItem;
                }

                // Categorize the news item based on the content 
                if (strpos(strtolower($item['title']), 'سياسة') !== false) {
                    $processedData['politics'][] = $newsItem;
                } elseif (strpos(strtolower($item['title']), 'اقتصاد') !== false) {
                    $processedData['economy'][] = $newsItem;
                } elseif (strpos(strtolower($item['title']), 'أمن') !== false || strpos(strtolower($item['title']), 'قضاء') !== false) {
                    $processedData['security'][] = $newsItem;
                }
            }

            return $processedData;
        }
    }

    private function formatTimestamp(string $dateString): string
    {
        $date = new \DateTime($dateString);
        $now = new \DateTime();
        $interval = $now->diff($date);

        if ($interval->d > 0) {
            return $interval->format('%d يوم مضت');
        } elseif ($interval->h > 0) {
            return $interval->format('%h ساعة مضت');
        } else {
            return $interval->format('%i دقيقة مضت');
        }
    }
}
