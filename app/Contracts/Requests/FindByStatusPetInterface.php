<?php

declare(strict_types=1);

namespace App\Contracts\Requests;

interface FindByStatusPetInterface
{
    public function getStatus(): string;
}
