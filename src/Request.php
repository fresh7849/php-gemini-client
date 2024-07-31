<?php

namespace Fresh\Gemini;

use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Http\Message\ResponseInterface;

class Request
{
    private string $baseUri = 'https://{location}-aiplatform.googleapis.com/v1/projects/{project_id}/locations/{location}/publishers/google/models/{model_id}:{method}';

    public function __construct(
        public string $projectId,
        public string $location,
        public string $accessToken,
        public ?string $modelId = '',
        public ?string $method = '',
    ) {}

    public function withMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function sendRequest(array $input, array $options = []): ResponseInterface
    {
        if (empty($this->method)) {
            throw new Exception('empty method');
        }
        $client = new GuzzleHttpClient;
        $url = str_replace(['{project_id}', '{location}', '{model_id}', '{method}'], [$this->projectId, $this->location, $this->modelId, $this->method], $this->baseUri);
        $data = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $input,
        ];
        if (! empty($options)) {
            $data = array_merge($data, $options);
        }

        try {
            $response = $client->post($url, $data);

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
