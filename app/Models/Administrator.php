<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Administrator
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Administrator extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = "admistrator";

    protected $fillable = ["name", "lastname", "email", "password"];
}
