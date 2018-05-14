<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number'
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
     * Get the bills list associated with the card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bills()
    {
        return $this->belongsToMany('App\Bills', 'bills', 'cards_id');
    }
}
