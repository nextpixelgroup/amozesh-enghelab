<?php
// app/Models/Restriction.php
namespace App\Models;

use App\Enums\UserRestrictionTypeEnum;
use App\traits\HandlesUserRestrictions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Restriction extends Model
{
    use HandlesUserRestrictions;
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {

        if ($this->expires_at && $this->expires_at->isFuture()) {
            return true;
        }

        return false;
    }

    protected function typeObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['type'] ?? '';
                $title = $value ? UserRestrictionTypeEnum::fromKey($value)->value : '';
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function expiresAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['expires_at'] ?? '';
                $title = $value ? verta()->instance($value)->format('Y/m/d H:i') : '';
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function createdAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['created_at'] ?? '';
                $title = verta()->instance($value)->format('Y/m/d H:i');
                return ['value' => $value, 'title' => $title];
            }
        );
    }

    protected function updatedAtObject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $value = $attributes['updated_at'] ?? '';
                $title = verta()->instance($value)->format('Y-m-d H:i');
                return ['value' => $value, 'title' => $title];
            }
        );
    }

}
