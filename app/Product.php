<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'experience', 'categories_id'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'categories_id');
    }

    /**
     * Get the ingredients list associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany('App\ProductIngredient', 'products_id', 'id');
    }
}
