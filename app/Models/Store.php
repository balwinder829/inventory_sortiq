<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * The user who owns the store.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }  

    public function items()
    {
        return $this->hasMany(Item::class);
    } 

     // Owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Users with roles in this store
    public function userRoles()
    {
        return $this->hasMany(UserStoreRole::class);
    }

     
}
