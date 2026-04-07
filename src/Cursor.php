<?php

namespace Chwnam\TRC;

class Cursor
{
    private string $dumpPath;

    public string $before = '';

    public string $after = '';

    public int $count = 0;

    public function __construct(Config $config)
    {
        $this->dumpPath = $config->dumpPath;

        if (file_exists($this->dumpPath . '/state')) {
            $decoded = json_decode(file_get_contents($this->dumpPath . '/state'));

            $this->before = $decoded->before ?? '';
            $this->after  = $decoded->after ?? '';
            $this->count  = $decoded->count ?? 0;
        }
    }

    public function saveState(): void
    {
        file_put_contents($this->dumpPath . '/state', json_encode([
            'before' => $this->before,
            'after'  => $this->after,
            'count'  => $this->count,
        ]));
    }
}