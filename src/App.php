<?php

namespace Chwnam\TRC;

use Exception;

class App
{
    const int REST = 30;

    public function run(): void
    {
        $config  = Config::init();
        $cursor  = new Cursor($config);
        $dumper  = new Dumper($config, $cursor);
        $fetcher = new RepliesFetcher($config, $cursor);
        $count   = 0;

        while (1) {
            try {
                $fetched = $fetcher->fetch();
            } catch (Exception $e) {
                echo $e->getMessage() . "\n" . "Sleeping for 30 seconds...\n";
                sleep(30);
                continue;
            }

            if (empty($fetched)) {
                break;
            }

            echo "Fetched page " . $cursor->count . ".\n";
            $dumper->dump($fetched);
            $cursor->saveState();

            if (0 === (++$count % self::REST)) {
                echo "Sleeping for 30 seconds...\n";
                sleep(30);
            } else {
                sleep($config->sleepTime);
            }
        }

        echo "Done!\n";
    }
}
