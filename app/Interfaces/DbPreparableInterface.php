<?php
namespace App\Interfaces;

interface DbPreparableInterface
{
    public function prepareForDB(array $data): array;
}
