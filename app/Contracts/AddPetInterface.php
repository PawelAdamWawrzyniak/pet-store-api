<?php

declare(strict_types=1);

namespace App\Contracts;


interface AddPetInterface
{
    public function getName(): string;

    public function getStatus(): string;

    public function getTagsIds(): array;

    public function getCategoryId(): int;

    public function requestAllData(): array;
}
