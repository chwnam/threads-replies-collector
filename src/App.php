<?php

namespace Chwnam\TRC;

class App
{
    public function run(): void
    {
        $config  = Config::init();
        $cursor  = new Cursor($config);
        $dumper  = new Dumper($config, $cursor);
        $fetcher = new RepliesFetcher($config, $cursor);

        while(1) {
            $fetched = $fetcher->fetch();
            if (empty($fetched)) {
                break;
            }
            echo "Fetched page " . $cursor->count . "\n";
            $dumper->dump($fetched);
            $cursor->saveState();
            sleep(3);
        }
        echo "Done!\n";
    }
}
