<?php

namespace App\Http\Integrations\Api;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class Replicate extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected string $apiToken,
    ){
        $this->withTokenAuth($this->apiToken, 'Token');
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.replicate.com/v1';
    }

    protected function defaultHeaders(): array
    {
        return [
            "Content-Type" => "application/json",
        ];
    }
}
