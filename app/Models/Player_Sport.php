<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player_Sport extends Model
{
    use HasFactory;
    protected $table = 'sport_player';
    protected $fillable = ['player','sport','branch_id','level_id','price_list'];
}
