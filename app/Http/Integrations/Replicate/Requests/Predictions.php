<?php

namespace App\Http\Integrations\Replicate\Requests;

use App\Http\Integrations\Replicate\DTOs\PredictionRecord;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Predictions extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private string $imageUrl)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/predictions';
    }

    protected function defaultBody(): array
    {
        return [
            'version' => '9283608cc6b7be6b65a8e44983db012355fde4132009bf99d976b2f0896856a3',
            'input' => [
                'version' => 'v1.4',
                'scale' => 2,
                'img' => $this->imageUrl,
            ]
        ];
    }

    public function createDtoFromResponse(Response $response): PredictionRecord
    {
        return PredictionRecord::fromResponse($response);
    }
}
