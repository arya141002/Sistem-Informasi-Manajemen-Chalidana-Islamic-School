<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    protected $fillable = ['parent_id', 'title', 'icon', 'url', 'role_access', 'order', 'is_active'];

    public function roles(): BelongsToMany
    {
        // Pastikan nama tabel pivotnya 'role_menu' sesuai yang kita buat tadi
        return $this->belongsToMany(Role::class, 'roles');
    }

    // Relasi untuk mengambil anak-anaknya (Submenu)
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Relasi untuk mengambil anak-anaknya secara rekursif (Submenu di dalam Submenu)
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    // Relasi untuk mengambil induknya
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
