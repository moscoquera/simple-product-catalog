<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{

    public $collects=CategoryResource::class;

    public function toArray($request)
    {
        return parent::toArray($request); // TODO: Change the autogenerated stub
    }


}
