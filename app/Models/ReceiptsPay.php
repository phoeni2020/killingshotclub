<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptsPay extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=[];
    protected $dates = ['date_receipt'];
    protected $table ='receipt';


    public  function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function receiptType(){
        return $this->belongsTo('App\Models\ReceiptTypePay' , 'from','id');
    }
    public function to(){
        return $this->belongsTo('App\Models\ReceiptTypePay' , 'to','id');
    }
}
