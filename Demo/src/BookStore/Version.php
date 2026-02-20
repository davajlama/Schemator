<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore;

enum Version: string
{
    case Version1 = 'Ver1';
    case Version2 = 'Ver2';
    case Version3 = 'Ver3';
}
