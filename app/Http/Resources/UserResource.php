<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            $this->mergeWhen(
                $this->relationLoaded('getRoles'),
                ['roles'=>$this->getRoles->roles_name]
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}