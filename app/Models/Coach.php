<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Coach
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coach whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Coach extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public mixed $users;
    protected $table = "coach";

    protected $fillable = ["name", "lastname", "email", "password"];


    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasAnyRole(...$roles){
        $token = $this->tokens()->first();

        if(!$token){
            return false;
        }

        $abilities = json_decode($token->abilities, true);

        foreach ($roles as $role){
            if(in_array("role:$role", $abilities)){
                return true;
            }
        }

        return response()->json(["message" => "Unauthorized role"]);
    }
}
