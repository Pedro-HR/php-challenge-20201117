<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'code' => Hashids::encode($this->id),
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'filename' => $this->filename,
            'height' => $this->height,
            'width' => $this->width,
            'price' => $this->price,
            'rating' => $this->rating,
            'date' => Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}
