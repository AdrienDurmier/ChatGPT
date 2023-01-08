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
