<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'company', 'phone', 'description'];
}
