<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDrop extends Model
{
    protected $table = 'users_drops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id', 'drops_id'
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
     * Get the associated user record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'users_id');
    }

    /**
     * Get the associated drop record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function drop()
    {
        return $this->hasOne('App\LevelDrop', 'id', 'drops_id');
    }
}
