<?php

namespace App\Http\Integrations\Replicate\Requests;

use App\Http\Integrations\Replicate\DTOs\Result;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class FetchPrediction extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $imageId)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/predictions/{$this->imageId}";
    }

    public function createDtoFromResponse(Response $response): Result
    {
        return Result::fromResponse($response);
    }
}
