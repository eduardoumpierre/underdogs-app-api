<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'experience', 'category', 'entity', 'value', 'drops_id'
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
     * Get the drop associated with the achievement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function drop()
    {
        return $this->hasOne('App\Drop', 'id', 'drops_id');
    }
}
