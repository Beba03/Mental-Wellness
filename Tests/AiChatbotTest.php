<?php

use PHPUnit\Framework\TestCase;

class AIChatbotTest extends TestCase
{
    private $apiUrl;

    protected function setUp(): void
    {
        $this->apiUrl = 'http://localhost/APIKey.php';
    }

    public function testAIChatbotResponse()
    {
        $userInput = 'Hello';

        $response = $this->sendRequest(['userInput' => $userInput]);

        $this->assertIsArray($response, 'Response should be an array');

        $aiResponse = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $this->assertNotEmpty($aiResponse, 'AI response should not be empty');
    }

    private function sendRequest(array $data)
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
