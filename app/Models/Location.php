<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    public function orders(){

        // $a = $this->hasMany(Order::class, 'location_id');
        // dd($a->get());

        return $this->hasMany(Order::class, 'location_id');
    }

    public function users(){
        $a = $this->hasMany(User::class, 'id');
        dd($a->get());
        
        return $this->hasMany(User::class, 'id');
    }
}
