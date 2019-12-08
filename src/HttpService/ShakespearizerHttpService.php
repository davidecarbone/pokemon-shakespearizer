<?php

namespace PokemonShakespearizer\HttpService;

use Gilbitron\Util\SimpleCache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;

class ShakespearizerHttpService
{
    /** @var ClientInterface */
    private $client;

    /** @var SimpleCache */
    private $cache;

    /**
     * @param ClientInterface $client
     * @param SimpleCache     $cache
     */
    public function __construct(ClientInterface $client, SimpleCache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @param string $descriptionId
     * @param string $description
     *
     * @return string
     * @throws ShakespearizerHttpServiceException
     * @throws ShakespearizerHttpServiceRequestLimitReachedException
     */
    public function shakespearizeDescription(string $descriptionId, string $description): string
    {
        try {
            if ($data = $this->cache->get_cache("pokemonDescription_$descriptionId")) {
                $responseBody = json_decode($data, true);
            } else {
                $response = $this->client->request('POST', "shakespeare.json", [
                    'form_params' => ['text' => $description]
                ]);

                $this->cache->set_cache("pokemonDescription_$descriptionId", $response->getBody());

                $responseBody = json_decode($response->getBody(), true);
            }
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
