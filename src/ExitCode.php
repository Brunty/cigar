<?php

declare(strict_types=1);

namespace Brunty\Cigar;

enum ExitCode: int
{
    case SUCCESS = 0;
    case FAILURE = 1;
}
