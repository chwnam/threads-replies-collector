<?php

namespace Chwnam\TRC;

use stdClass;
use WpOrg\Requests\Requests;

class RepliesFetcher implements Fetcher
{
    public const string API_ENDPOINT = 'https://graph.threads.com/v1.0';

    readonly private string $accessToken;
    readonly private string $userId;
    readonly private string $fields;
    readonly private Cursor $cursor;

    public function __construct(Config $config, Cursor $cursor)
    {
        $this->accessToken = $config->accessToken;
        $this->userId      = $config->userId;
        $this->fields      = implode(',', [
            'id',
            'permalink',
            'text',
            'timestamp',
            'replied_to',
            'root_post',
        ]);

        $this->cursor = $cursor;
    }

    public function fetch(): array
    {
        $baseUrl = self::API_ENDPOINT . "/$this->userId/replies";
        $url     = $baseUrl . "?access_token=$this->accessToken&limit=100&fields=$this->fields";
        if ($this->cursor->after) {
            $url .= "&after={$this->cursor->after}";
        }

        $r = Requests::get($url);
        if (200 !== $r->status_code) {
            throw new \RuntimeException("Failed to fetch replies: {$r->status_code} {$r->body}");
        }

        $decoded = json_decode($r->body, true);

        $this->cursor->after  = $decoded['paging']['cursors']['after'] ?? '';
        $this->cursor->before = $decoded['paging']['cursors']['before'] ?? '';
        $this->cursor->count  += 1;

        return $decoded;
    }
}
