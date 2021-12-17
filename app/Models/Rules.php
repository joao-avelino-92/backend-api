<?php

namespace App\Models;

use Spatie\Enum\HasEnums;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    protected $fillable = ['rule', 'user_id'];
}
