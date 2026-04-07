<?php

namespace Chwnam\TRC;

use Dotenv\Dotenv;

readonly final class Config
{
    public string $accessToken;
    public string $userId;
    public string $dumpPath;
    public int $sleepTime;

    private function __construct()
    {
        // loads the .env file
        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->load();
        $dotenv
            ->required(['ACCESS_TOKEN', 'USER_ID', 'DUMP_PATH', 'SLEEP_TIME'])
            ->notEmpty();

        $this->accessToken = $_ENV['ACCESS_TOKEN'];
        $this->userId      = $_ENV['USER_ID'];
        $this->dumpPath    = $_ENV['DUMP_PATH'];
        $this->sleepTime   = (int)abs($_ENV['SLEEP_TIME']) ?: 5;

        if ( ! file_exists($this->dumpPath) || ! is_dir($this->dumpPath) || ! is_writable($this->dumpPath)) {
            die('DUMP_PATH is not a valid directory. It should be a writable directory.');
        }
    }

    public static function init(): self
    {
        return new self();
    }
}
