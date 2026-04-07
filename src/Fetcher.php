<?php

namespace Chwnam\TRC;

use stdClass;

interface Fetcher
{
    public function fetch(): array;
}