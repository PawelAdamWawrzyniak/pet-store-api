<?php

declare(strict_types=1);

namespace App\Contracts\Requests;


interface AddPetInterface
{
    public function getName(): string;

    public function getStatus(): string;

    public function getTags(): array;

    public function getCategory(): string;
    public function getPhotoUrls(): array;

    public function requestAllData(): array;
}
