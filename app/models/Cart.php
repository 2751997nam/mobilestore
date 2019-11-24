<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "cart";
    protected $fillable = [
        'token',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}