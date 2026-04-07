<?php

namespace Chwnam\TRC;

use WpOrg\Requests\Requests;

class RepliesFetcher implements Fetcher
{
    public const API_ENDPOINT = 'https://graph.threads.com/v1.0';

    readonly private array $fields;

    public function __construct(
        readonly private string $accessToken,
        readonly private string $userId,
        private string          $lastCursor,
    )
    {
        $this->fields = [
            'id',
            'permalink',
            'text',
            'replied_to',
        ];
    }

    public function fetch(): array
    {
        $baseUrl = self::API_ENDPOINT . "/$this->userId/replies";
        $fields  = implode(',', $this->fields);
        $url     = $baseUrl . "?access_token=$this->accessToken&limit=100&fields=$fields";

        $r = Requests::get($url);

        if (200 !== $r->status_code) {
            throw new \RuntimeException("Failed to fetch replies: {$r->status_code} {$r->body}");
        }

        return json_decode($r->body, true);
    }

    public function getLastCursor(): string
    {
        return $this->lastCursor;
    }
}