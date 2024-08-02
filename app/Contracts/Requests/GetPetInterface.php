<?php

declare(strict_types=1);

namespace App\Contracts\Requests;


interface GetPetInterface
{
    public function getId(): int;
}
