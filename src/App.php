<?php

namespace Chwnam\TRC;

class App
{
    public function run(): void
    {
        $config = Config::init();

        // 마지막 페이지로부터 읽어들인다
        $fetcher = new RepliesFetcher(
            accessToken: $config->accessToken,
            userId: $config->userId,
            lastCursor: $config->lastCursor,
        );

        // Dump
        $fetched = $fetcher->fetch();
        // $path    = sprintf('%s/%04d.json', $config->dumpPath, $config->count);
        // file_put_contents($path, json_encode($fetched));
        var_dump($fetched);

        // Update state
        // $config->lastCursor = $fetcher->getLastCursor();
        // $config->count      += 1;
    }
}
