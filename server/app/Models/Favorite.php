<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'media_type',
        'media_id',
        'user_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder $builder
     * @param MediaType|null $type
     * @return Builder
     */
    public function scopeMediaType(Builder $builder, ?MediaType $type): Builder
    {
        return $builder->when($type, fn (Builder $q) => $q->where("media_type", $type->value));
    }

    /**
     * @param Builder $builder
     * @param integer|null $id
     * @return Builder
     */
    public function scopeMediaId(Builder $builder, ?int $id): Builder
    {
        return $builder->when($id, fn (Builder $q) => $q->where("media_id", $id));
    }

    /**
     * @param Builder $builder
     * @param integer|null $id
     * @return Builder
     */
    public function scopeUserId(Builder $builder, ?int $id): Builder
    {
        return $builder->when($id, fn (Builder $q) => $q->where("user_id", $id));
    }
}
