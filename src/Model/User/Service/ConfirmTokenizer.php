<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Rhumsaa\Uuid\Uuid;

class ConfirmTokenizer
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}