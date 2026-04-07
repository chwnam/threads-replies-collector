<?php

namespace Chwnam\TRC;

use stdClass;

readonly class Dumper
{
    public function __construct(
        private Config $config,
        private Cursor $cursor
    ) {
    }

    public function dump(stdClass|array $data): void
    {
        $path    = sprintf('%s/%04d.json', $this->config->dumpPath, $this->cursor->count);
        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        file_put_contents($path, $encoded);
    }
}