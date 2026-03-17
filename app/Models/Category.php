<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'wallpaper_count', 'parent_id'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Parent category relationship (for subcategories)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories (subcategories)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function wallpapers()
    {
        return $this->belongsToMany(Wallpaper::class, 'category_wallpaper');
    }

    // Check if category is a parent (has subcategories)
    public function isParent()
    {
        return $this->children()->exists();
    }

    // Check if category is a subcategory
    public function isChild()
    {
        return $this->parent_id !== null;
    }
}
