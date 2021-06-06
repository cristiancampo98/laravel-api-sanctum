<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'Correo' => $this->email,
            'Estado del usuario' => $this->user_status ? 'Activo' : 'Suspendido',
            'Estado de alerta' => $this->user_alert ? $this->user_alert : 'Sin definir'
        ];
    }
}
