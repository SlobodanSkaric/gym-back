<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Coach\CoachGetWithUser;
use App\Http\Resources\TrainingProgram\TrainingProgramResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGetResource extends JsonResource
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
            "id"        => $this->id,
            "name"      => $this->name,
            "lastname"  => $this->lastname,
            "email"     => $this->email,
            "status"    => $this->getStatusAndUpdatePay($this->status, null),
            "coach"     => $this->coach ?  new CoachGetWithUser($this->coach ) : null,
            "training"  => $this->training_programs->map(function($data) {
                return [
                    "id"                => $data->id,
                    "program_name"      => $data->program_name,
                    "trening_weight"    => $data->trening_weight
                ];
            })
        ];
    }

    private function getStatusAndUpdatePay($status, $datetime){
           if ($status == 0 ) return false;

            return true;

            /*TODO Implement calculate update payment*/
    }
}
