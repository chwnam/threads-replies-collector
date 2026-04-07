<?php

namespace Chwnam\TRC;

use Dotenv\Dotenv;

final class Config
{
    readonly public string $accessToken;
    readonly public string $userId;
    readonly public string $dumpPath;

    public string $lastCursor;
    public int    $count;

    public function __construct()
    {
        // loads the .env file
        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->load();
        $dotenv
            ->required(['ACCESS_TOKEN', 'USER_ID', 'DUMP_PATH'])
            ->notEmpty();

        $this->accessToken = $_ENV['ACCESS_TOKEN'];
        $this->userId      = $_ENV['USER_ID'];
        $this->dumpPath    = $_ENV['DUMP_PATH'];

        if (!file_exists($this->dumpPath) || !is_dir($this->dumpPath) || !is_writable($this->dumpPath)) {
            die('DUMP_PATH is not a valid directory. It should be a writable directory.');
        }

        if (file_exists($this->dumpPath . '/state')) {
            $decoded = json_decode(file_get_contents($this->dumpPath . '/state'));

            $this->lastCursor = $decoded->last_cursor ?? '';
            $this->count      = $decoded->count ?? 0;
        } else {
            $this->lastCursor = '';
            $this->count      = 0;
        }
    }

    public function saveState(): void
    {
        file_put_contents($this->dumpPath . '/state', json_encode([
            'last_cursor' => $this->lastCursor,
            'count'       => $this->count,
        ]));
    }

    public static function init(): self
    {
        return new self();
    }
}
