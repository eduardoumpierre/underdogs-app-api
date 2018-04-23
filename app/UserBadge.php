<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $table = 'users_badges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id', 'badges_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Get the product record associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'users_id');
    }

    /**
     * Get the product record associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function badge()
    {
        return $this->hasOne('App\Badge', 'id', 'badges_id');
    }
}
