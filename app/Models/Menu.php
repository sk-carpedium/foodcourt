<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'availability_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'availability_hours' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($menu) {
            if (empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function activeCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class)->where('is_active', true);
    }

    public function activeItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->where('is_available', true);
    }
}