<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'name'=>$this->name,
          'image'=>Storage::url($this->image),
          'parent_id'=>$this->parent_id,
          'parent'=>CategoryResource::make($this->whenLoaded('parent')),
          'childs'=>CategoryCollection::make($this->whenLoaded('childs'))
        ];
    }
}
