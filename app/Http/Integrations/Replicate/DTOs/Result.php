<?php

namespace App\Http\Integrations\Replicate\DTOs;

use Saloon\Contracts\Response;

class Result
{
    public const STATUS_SUCCEEDED = "succeeded";
    public const STATUS_FAILED = "failed";
    public const DONE_STATUSES = [
        self::STATUS_SUCCEEDED,
        self::STATUS_FAILED,
    ];

    public function __construct(
        public readonly string  $status,
        public readonly ?string $output,
    )
    {
        //
    }

    public function processing(): bool
    {
        return !in_array($this->status, self::DONE_STATUSES);
    }

    public static function fromResponse(Response $response): self
    {
        return new static($response->json('status'), $response->json('output', ""));
    }
}
