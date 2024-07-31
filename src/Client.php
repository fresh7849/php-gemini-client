<?php

namespace Fresh\Gemini;

use Exception;
use Fresh\Gemini\Resources\Chat;
use Fresh\Gemini\Resources\Token;
use Fresh\Gemini\Response\StreamResponse;
use Fresh\Gemini\Response\Token\ResponseToken;
use Google\Auth\Credentials\ServiceAccountCredentials;

/**
 * Gemini客服端.
 */
class Client
{
    protected Request $request;

    public function __construct(
        public readonly string $projectId,
        public readonly string $location,
        public readonly ?string $credentialPath = '',
        public readonly ?string $modelId = '',
    ) {
        $accessToken = isset($credentialPath) ? static::accessToken($credentialPath) : '';
        $this->request = new Request($projectId, $location, $accessToken, $modelId);
    }

    public static function instance(string $projectId, string $location, ?string $credentialPath = '', ?string $modelId = ''): Client
    {
        return new self($projectId, $location, $credentialPath, $modelId);
    }

    public static function accessToken(?string $credentialPath = ''): string
    {
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

        if (empty($credentialPath)) {
            $credentialPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
            if (empty($credentialPath)) {
                throw new Exception('empty credential file path');
            }
        }
        $credentials = new ServiceAccountCredentials($scopes, $credentialPath);
        $token = $credentials->fetchAuthToken();
        if ($token) {
            $tokenInfo = $credentials->getLastReceivedToken();

            return $tokenInfo['access_token'];
        }
        throw new Exception('empty token');
    }

    public function withAccessToken(string $accessToken): self
    {
        $this->request->accessToken = $accessToken;

        return $this;
    }

    public function withModelId(string $modelId): self
    {
        $this->request->modelId = $modelId;

        return $this;
    }

    public function countTokens(array $content): ResponseToken
    {
        return (new Token($this->request))->countTokens($content);
    }

    public function streamGenerateContent(array $input): StreamResponse
    {
        return (new Chat($this->request))->streamGenerateContent($input);
    }
}
