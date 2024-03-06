<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptTypes extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table ='receipt';
    public function branches() {

        return $this->belongsTo('App\Models\Branchs','branch_id','id');
    }
}
