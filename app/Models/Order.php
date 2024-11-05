<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_generate',
        'location_id',
        'user_id',
        'payment_method_id',
    ];

    public function getOrderGenerateAttribute(){
        return 'Order #' . $this->id . '';
    } 

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Post::class);
    }

    public function products(){
        return $this->belongsToMany(Post::class, 'order_has_products');
    }

    public function scopeWhereLocationMatchesUserLocation($query, User $userModel) {
        //For super admin
        if($userModel->isAdmin()){
            return $query->select('orders.*');
        }

        //For locationwise admin
        return $query->where('orders.location_id', $userModel->location_id)
                ->select('orders.*');
    }
}
