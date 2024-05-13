<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpolyeeAttendance extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table='employee_attendances';

    public function traniers(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
