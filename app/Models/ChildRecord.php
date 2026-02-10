<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'children_id',
        'lingkar',
        'weight',
        'length'
    ];
}
