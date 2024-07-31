<?php

namespace Fresh\Gemini\Resources;

use Fresh\Gemini\Request;
use Fresh\Gemini\Response\Token\ResponseToken;

class Token
{
    public function __construct(
        private Request $request,
    ) {}

    /**
     * 获取词元.
     *
     * @param  array  $content  parts内容
     *
     * @link model参数参考：https://cloud.google.com/vertex-ai/generative-ai/docs/model-reference/inference?hl=zh-cn#supported_models
     * @link https://cloud.google.com/vertex-ai/generative-ai/docs/multimodal/get-token-count#gemini-get-token-count-samples-drest
     */
    public function countTokens(array $content): ResponseToken
    {
        $method = 'countTokens';
        $input = [
            'contents' => [
                'role' => 'USER',
                'parts' => $content,
            ],
        ];
        $response = $this->request->withMethod($method)->sendRequest($input);
        $responseBody = $response->getBody()->getContents();

        return ResponseToken::from(json_decode($responseBody, true));
    }
}
