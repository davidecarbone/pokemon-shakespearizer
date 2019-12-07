<?php

namespace PokemonShakespearizer\HttpService;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;

class ShakespearizerHttpService
{
    /** @var ClientInterface */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $description
     *
     * @return string
     * @throws ShakespearizerHttpServiceException
     * @throws ShakespearizerHttpServiceRequestLimitReachedException
     */
    public function shakespearizeDescription(string $description): string
    {
        try {
            $response = $this->client->request('POST', "shakespeare.json", [
                'form_params' => ['text' => $description]
            ]);
            $responseBody = json_decode($response->getBody(), true);
        } catch (BadResponseException $exception) {
            $responseCode = $exception->hasResponse() ? $exception->getResponse()->getStatusCode() : null;
            $responseBody = $exception->hasResponse() ? $exception->getResponse()->getBody()->getContents() : '';
            $responseBody = json_decode($responseBody, true);

            if (429 === $responseCode) {
                throw new ShakespearizerHttpServiceRequestLimitReachedException($responseBody['error']['message']);
            }

            if (200 !== $responseCode) {
                throw new ShakespearizerHttpServiceException('Failed to shakespearize description.');
            }
        }

        return $responseBody['contents']['translated'] ?? '';
    }
}
