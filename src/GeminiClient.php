<?php

namespace Baishu\GeminiClient;

use App\Repositories\Enum\GptModel;
use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Baishu\GeminiClient\Response\StreamResponse;

/**
 * Gemini客服端.
 */
class GeminiClient
{
    /**
     * construct
     *
     * @param string $projectId
     * @param string $location
     * @param string|null $credentialPath  JSON credential file path
     */
    public function __construct(
        public readonly string $projectId,
        public readonly string $location,
        public readonly ?string $credentialPath = ''
    ) {}

    /**
     * 获取词元.
     *
     * @param  array  $content  parts内容
     *
     * @link model参数参考：https://cloud.google.com/vertex-ai/generative-ai/docs/model-reference/inference?hl=zh-cn#supported_models
     * @link https://cloud.google.com/vertex-ai/generative-ai/docs/multimodal/get-token-count#gemini-get-token-count-samples-drest
     *
     * @return array ["totalTokens":1,"totalBillableCharacters":4]
     */
    public function countTokens(array $content, string $model): array
    {
        $client = new Client;

        // API endpoint URL
        $url = sprintf('https://%s-aiplatform.googleapis.com/v1/projects/%s/locations/%s/publishers/google/models/%s:countTokens', $this->location, $this->projectId, $this->location, $model);

        // Prepare the request payload
        $input = [
            'contents' => [
                'role' => 'USER',
                'parts' => $content,
            ],
        ];

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => $input,
            ]);
            $responseBody = $response->getBody()->getContents();

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 流式传输聊天API.
     *
     * @param  GptModel  $model
     *
     * @link model参数参考：https://cloud.google.com/vertex-ai/generative-ai/docs/model-reference/inference#supported_models
     * @link https://cloud.google.com/vertex-ai/generative-ai/docs/multimodal/send-multimodal-prompts
     */
    public function streamGenerateContent(array $input, string $model): StreamResponse
    {
        $client = new Client;

        // API endpoint URL
        $url = sprintf('https://%s-aiplatform.googleapis.com/v1/projects/%s/locations/%s/publishers/google/models/%s:streamGenerateContent', $this->location, $this->projectId, $this->location, $model);

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => $input,
                'stream' => true,
            ]);

            // 获取响应主体作为流
            return new StreamResponse($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

        if (empty($this->credentialPath)) {
            $this->credentialPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
            if (empty($this->credentialPath)) {
                throw new Exception('empty credential file path');
            }
        }
        $credentials = new ServiceAccountCredentials($scopes, $this->credentialPath);
        $token = $credentials->fetchAuthToken();
        if ($token) {
            $tokenInfo = $credentials->getLastReceivedToken();

            return $tokenInfo['access_token'];
        }
        throw new Exception('empty token');
    }
}
