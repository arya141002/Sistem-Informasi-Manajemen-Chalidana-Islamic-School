<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // Mass assignment
    protected $fillable = ['name', 'display_name'];

    /**
     * Hubungan ke User: Satu role bisa dimiliki banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Hubungan ke Menu (Many-to-Many):
     * Satu role bisa mengakses banyak menu, satu menu bisa diakses banyak role.
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu');
    }
}