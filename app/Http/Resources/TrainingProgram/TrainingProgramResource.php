<?php

namespace App\Http\Resources\TrainingProgram;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingProgramResource extends JsonResource
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
            "id"                => $this->id,
            "program_name"      => $this->program_name,
            "training_weight"   => $this->trening_weight
        ];
    }
}
