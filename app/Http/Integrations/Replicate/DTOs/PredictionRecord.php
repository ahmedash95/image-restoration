<?php

namespace App\Http\Integrations\Replicate\DTOs;

use Saloon\Contracts\Response;

class PredictionRecord
{
    public function __construct(
        public readonly string $imageId,
    )
    {
        //
    }

    public static function fromResponse(Response $response): self
    {
        $path = explode('/', $response->json('urls')['get']);

        return new static(end($path));
    }
}
