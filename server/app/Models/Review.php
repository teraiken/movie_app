<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'rating',
        'user_id',
        'media_id',
        'media_type'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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
     * @param MediaType|null $type
     * @return Builder
     */
    public function scopeMediaType(Builder $builder, ?MediaType $type): Builder
    {
        return $builder->when($type, fn (Builder $q) => $q->where("media_type", $type->value));
    }
}
