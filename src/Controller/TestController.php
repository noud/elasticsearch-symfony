<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test/auth", methods={"POST","HEAD"})
     */
    public function auth(Request $request)
    {
         $answer = [
            "auth" => true
        ];
       return $this->suggestShared($request, $answer);
    }

    /**
     * @Route("/test/_suggest", methods={"POST","HEAD"})
     */
    public function suggest(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $toSearch = $data["keywordSuggester"]["prefix"];
            $request->request->replace(is_array($data) ? $data : array());
        }

// @todo query ELastic
// transform 

        $suggestions = [
            "keywordSuggester" => [
                [
                    "options" => [
                        // [
                        //     'text' => 'Java',
                        //     'score' => 1
                        // ],
                        [
                            'text' => 'JavaScript',
                            'score' => 10
                        ],
                        [
                            'text' => 'PHP',
                            'score' => 5
                        ]
                    ]
                ]
            ]
        ];
       return $this->suggestShared($request, $suggestions);
    }

    /**
     * @Route("/test/_search", methods={"POST","HEAD"})
     */
    public function search(Request $request)
    {
        $result = [
            "keywordSuggester" => [
                [
                    "options" => [
                        [
                            'text' => 'een',
                            'score' => 3
                        ],
                        [
                            'text' => 'twee',
                            'score' => 13
                        ]
                    ]
                ]
            ]
        ];
        return $this->searchShared($request, $result);
    }

    private function suggestShared(Request $request, $suggestions)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    
        $response = new Response(json_encode($suggestions));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function searchShared(Request $request, $result)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
 
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}