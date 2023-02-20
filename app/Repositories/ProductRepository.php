<?php

namespace App\Repositories;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;
use App\Interfaces\CrudInterface;
use App\Interfaces\DbPreparableInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use TheSeer\Tokenizer\Exception;

class ProductRepository implements CrudInterface, DbPreparableInterface
{
    use ResponseTrait;
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);
        $query = Product::orderBy($filter['orderBy'], $filter['order']);
        if(!empty($filter['search'])){
            $query->where(function($query) use ($filter){
                $searched = '%'.$filter['search'].'%';
                $query->where('title', 'like', $searched)->orWhere('slug', 'like', $searched);
            });
        }
        return $query->paginate($filter['perPage']);
    }
    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];
        return array_merge($defaultArgs, $filterData);
    }
    public function getById(int $id)
    {
        $product = Product::where('id', $id)->first();
        if(empty($product)){
            throw new Exception('Product not found', Response::HTTP_NOT_FOUND);
        }
        return $product;
    }
    public function create(array $data): ?Product
    {
        $data = $this->prepareForDB($data);
        return Product::create($data);
    }
    public function update(int $id, array $data): ?Product
    {
        $product = $this->getById($id);
        $updated = $product->update($this->prepareForDB($data, $product));
        if($updated){
            $product = $this->getById($id);
        }
        return $product;
    }
    public function delete(int $id): ?Product
    {
        $product = $this->getById($id);
        $this->deleteImage($product->image);
        $deleted = $product->delete();
        if(!$deleted){
            throw new Exception("Product could not be deleted", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $product;
    }
    public function prepareForDB(array $data, ?Product $product = null): array
    {
        if(empty($data['slug'])){
            $data['slug'] = $this->createUniqueSlug($data['title']);
        }

        if(!empty($data['image'])){
            if(!is_null($product)){
                // delete the previous image
                $this->deleteImage($product->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        $data['user_id'] = Auth::user()->id;

        return $data;
    }
    private function createUniqueSlug(string $title)
    {
        return Str::slug(substr($title, 0, 80)).'-'.time();
    }
    private function uploadImage($image)
    {
        $name = time() . '.' . $image->extension();
        $image->storePubliclyAs('public', $name);
        return $name;
    }
    private function deleteImage(string $imageName): void
    {
        if (!empty($image) && Storage::exists($imageName)){
            Storage::delete($imageName);
        }
    }
}
