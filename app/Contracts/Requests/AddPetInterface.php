<?php

declare(strict_types=1);

namespace App\Contracts\Requests;


interface AddPetInterface
{
    public function getName(): string;

    public function getStatus(): string;

    public function getTagsIds(): array;

    public function getCategoryId(): int;
    public function getPhotoUrls(): array;

    public function requestAllData(): array;
}
