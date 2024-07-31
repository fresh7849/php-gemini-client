<?php

namespace Fresh\Gemini\Resources;

use Fresh\Gemini\Request;
use Fresh\Gemini\Response\StreamResponse;

class Chat
{
    public function __construct(
        private Request $request,
    ) {}

    /**
     * 流式传输聊天API.
     *
     *
     * @link https://cloud.google.com/vertex-ai/generative-ai/docs/multimodal/send-multimodal-prompts
     */
    public function streamGenerateContent(array $input): StreamResponse
    {
        $method = 'streamGenerateContent';
        $response = $this->request->withMethod($method)->sendRequest($input, ['stream' => true]);

        return new StreamResponse($response);
    }
}
