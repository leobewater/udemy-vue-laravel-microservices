<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User
{
    // use HasApiTokens, HasFactory, Notifiable;
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array
//     */
//    protected $fillable = [
//        'first_name',
//        'last_name',
//        'email',
//        'password',
//        'is_influencer'
//    ];
//
//    /**
//     * The attributes that should be hidden for arrays.
//     *
//     * @var array
//     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];
//
//    /**
//     * The attributes that should be cast to native types.
//     *
//     * @var array
//     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $is_influencer;

    public function __construct($json)
    {
        $this->id = $json['id'];
        $this->first_name = $json['first_name'];
        $this->last_name = $json['last_name'];
        $this->email = $json['email'];
        $this->is_influencer = $json['is_influencer'] ?? 0;
    }

    public function role()
    {
        $userRole = UserRole::where('user_id', $this->id)->first();
        return Role::find($userRole->role_id);

        // return $this->hasOneThrough(Role::class, UserRole::class, 'user_id', 'id', 'id', 'role_id');
    }

    public function permissions()
    {
        return $this->role()->permissions->pluck('name');
    }

    public function hasAccess($access)
    {
        return $this->permissions()->contains($access);
    }

    public function isAdmin():bool
    {
        return $this->is_influencer === 0;
    }

    public function isInfluencer():bool
    {
        return $this->is_influencer === 1;
    }

    // get influencer total revenue
    public function revenue()
    {
        $orders = Order::where('user_id', $this->id)->where('complete', 1)->get();

        return $orders->sum(function(Order $order) {
            return $order->influencer_total;
        });
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
