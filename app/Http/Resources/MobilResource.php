<?php

namespace App\Http\Resources;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobilResource extends JsonResource
{
    //define property
    public $status;
    public $message;
    public $resource;

    /**
     * __construct
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }
    
     /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
           
        ];
    }
}
