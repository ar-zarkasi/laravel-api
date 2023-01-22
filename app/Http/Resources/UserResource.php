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
            'id_roles' => $this->id_roles,
            $this->mergeWhen(
                $this->whenLoaded('roles'),
                ['roles'=>$this->roles->roles_name]
            ),
            $this->mergeWhen(
                $this->whenLoaded('token_table'),
                ['has_token'=>$this->token_table]
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}