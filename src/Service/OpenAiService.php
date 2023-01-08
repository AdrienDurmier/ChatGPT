<?php

namespace App\Service;

use Exception;
use Orhanerday\OpenAi\OpenAi;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OpenAiService
{
    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @param string $question
     *
     * @return string
     *
     * @throws Exception
     */
    public function getAiResponse(string $question): string
    {
        $open_ai_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($open_ai_key);

        sleep(2);
        return 'bla bla bla...';

        $complete = $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => $question,
            'temperature' => 0,
            'max_tokens' => 3500,
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ]);

        $json = json_decode($complete, true);

        if (isset($json['choices'][0]['text'])){
            return $json['choices'][0]['text'];
        }

        return sprintf(
            'An error occurred with OpenAI. Type: %s, Code: %s, Error message: %s',
            $json['error']['type'],
            $json['error']['code'],
            $json['error']['message']
        );
    }
}
