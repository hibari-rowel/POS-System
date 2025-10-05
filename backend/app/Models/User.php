<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'users';

    public $incrementing = false;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'role',
        'status',
        'email',
        'password',
        'image_name',
        'original_image_name',
        'image_extension',
        'image_mime_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'email_verified_at',
    ];

    protected $appends = [
        'image',
        'full_name',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getFieldValidations($params): array
    {
        $userID = !empty($params['user_id']) ? $params['user_id'] : null;

        return [
            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'role' => ['required', new Enum(UserRoleEnum::class)],
            'status' => ['required', new Enum(UserStatusEnum::class)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($userID)->whereNull('deleted_at')],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'confirm_password' => ['required', 'same:password'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10048'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }

    // ##################### Accessors & Mutators ######################
    protected function fullName(): Attribute
    {
        return new Attribute(
            get: function () {
                $fullname = [];
                if (!empty($this->getOriginal('first_name'))) {
                    $fullname[] = $this->getOriginal('first_name');
                }
                if (!empty($this->getOriginal('middle_name'))) {
                    $fullname[] = $this->getOriginal('middle_name');
                }
                if (!empty($this->getOriginal('last_name'))) {
                    $fullname[] = $this->getOriginal('last_name');
                }

                return implode(' ', $fullname);
            },
        );
    }

    protected function image(): Attribute
    {
        return new Attribute(
            get: function() {
                    return !empty($this->getOriginal('image_name'))
                        ? asset("storage/uploads/users/profile_image/" . $this->getOriginal('image_name') . '.' . $this->getOriginal('image_extension'))
                        : null;
                },
        );
    }
}
