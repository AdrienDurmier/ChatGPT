<?php

namespace App\Controller;

use App\Form\ChatGptType;
use App\Service\OpenAiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, OpenAiService $openAiService): Response
    {
        // @see for disable ssl verification, else it always returns false: https://github.com/orhanerday/open-ai/issues/38
        $form = $this->createForm(ChatGptType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $json = $openAiService->getAiResponse($data['question']);

            return $this->render('home/response.html.twig', [
                'json' => $json ?? null,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
