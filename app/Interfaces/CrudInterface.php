<?php
namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
interface CrudInterface
{
    public function getAll(array $filterData): Paginator;
    public function getById(int $id);
    public function create(array $data): ?Product;
    public function update(int $id, array $data): ?Product;
    public function delete(int $id): ?Product;

}
