<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelDrop extends Model
{
    protected $table = 'levels_drops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'levels_id', 'drops_id'
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
     * Get the associated level record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function level()
    {
        return $this->hasOne('App\Level', 'id', 'levels_id');
    }

    /**
     * Get the associated drop record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function drop()
    {
        return $this->hasOne('App\Drop', 'id', 'drops_id');
    }
}
