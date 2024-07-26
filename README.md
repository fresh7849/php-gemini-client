# php-gemini-client

使用PHP封装了部分gemini rest api

## Table of Contents

- [Get Started](#get-started)
- [Usage](#usage)
  - [countTokens](#counttokens)
  - [streamGenerateContent](#streamgeneratecontent)

## Get Started

> **Requires [PHP 8.1+](https://php.net/releases/)**

First, install Gemini via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require baishu/gemini-client
```

Then, interact with Gemini's API:

```php
$path = '/gcs.json';
$projectId = 'test';
$location = 'us-central1';
$client = new GeminiClient($projectId,$location,$path);
```

## Usage

### countTokens

```php
$data = [['text' => 'test']];
$model = 'gemini-1.5-flash-001';
$res = $client->countTokens($data,$model);

echo json_encode($res);
// result: {"totalTokens":1,"totalBillableCharacters":4}
```

### streamGenerateContent

```php
$payload = [
    'systemInstruction' => [
        'parts' => [
            [
                'text' => '你是一名英文翻译官，请把用户发送的内容翻译成英文。',
            ],
        ],
    ],
    'contents' => [
        [
            'role' => 'USER',
            'parts' => [
                [
                    'text' => '你叫什么名字？',
                ],
            ],
        ],
    ],
    'generationConfig' => [
        'temperature' => 1,
        'responseMimeType' => 'text/plain',
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH',
            'threshold' => 'BLOCK_ONLY_HIGH',
        ],
        [
            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
            'threshold' => 'BLOCK_ONLY_HIGH',
        ],
        [
            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'threshold' => 'BLOCK_ONLY_HIGH',
        ],
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_ONLY_HIGH',
        ],
    ],
];
$response = $client->streamGenerateContent($payload, $model);
/** @var \Baishu\GeminiClient\Response\Chat\ResponseChunk $responseItem */
foreach ($response as $responseItem) {
    echo json_encode($responseItem->toArray()).PHP_EOL.PHP_EOL;
}

// result:
// {"candidates":[{"content":{"role":"model","parts":[{"text":"What"}]}}]}

// {"candidates":[{"content":{"role":"model","parts":[{"text":"'s your name? \n"}]},"safetyRatings":[{"category":"HARM_CATEGORY_HATE_SPEECH","probability":"NEGLIGIBLE","probabilityScore":0.09947021,"severity":"HARM_SEVERITY_NEGLIGIBLE","severityScore":0.10502681},{"category":"HARM_CATEGORY_DANGEROUS_CONTENT","probability":"NEGLIGIBLE","probabilityScore":0.1317307,"severity":"HARM_SEVERITY_NEGLIGIBLE","severityScore":0.09073549},{"category":"HARM_CATEGORY_HARASSMENT","probability":"NEGLIGIBLE","probabilityScore":0.2155158,"severity":"HARM_SEVERITY_NEGLIGIBLE","severityScore":0.07821887},{"category":"HARM_CATEGORY_SEXUALLY_EXPLICIT","probability":"NEGLIGIBLE","probabilityScore":0.07544843,"severity":"HARM_SEVERITY_NEGLIGIBLE","severityScore":0.06791668}]}]}

// {"candidates":[{"content":{"role":"model","parts":[{"text":""}]},"finishReason":"STOP"}],"usageMetadata":{"promptTokenCount":21,"candidatesTokenCount":8,"totalTokenCount":29}}
```
