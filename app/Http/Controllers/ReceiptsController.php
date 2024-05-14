<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
        use App\Models\Packages;
        use App\Models\Players;
        use App\Models\PriceList;
        use App\Models\Receipts;
        use App\Http\Requests\StoreReceiptsRequest;
        use App\Http\Requests\UpdateReceiptsRequest;
        use App\Models\ReceiptsPay;
        use App\Models\ReceiptTypes;
use App\Models\StadiumsRentTable;
use App\Models\User;
use Facade\FlareClient\Http\Response;
        use Illuminate\Http\Request;
        use App\Services\PDF\ConvertDataToPDF;
use Illuminate\Support\Facades\DB;
use ZanySoft\LaravelPDF\PDF;
        use App\Exports\ExportToExcelSheet;
        use Maatwebsite\Excel\Facades\Excel;
  class ReceiptsController extends Controller{
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            if(\Auth::user()->hasRole('administrator')){
                $branchIds = Branchs::get()->pluck('id')->toArray();
            }
            else{
                $branchIds = \Auth::user()->branches->pluck('id')->toArray();
            }
            $filterMail = $request->mail;
            $filterName = $request->name;
            $filterPhone = $request->phone;
            $recipt_id = $request->recipt_id;

            if(!empty($filterName)||!empty($filterMail) || !empty($filterPhone)){

                $player = Players::query();
                if(!empty($filterName))
                    $player->orWhere('name', 'like', '%' .$filterName . '%');

                if(!empty($filterPhone))
                    $player->orWhere('father_phone', 'like', '%' . $filterPhone . '%')
                        ->orWhere('anther_phone', 'like', '%' . $filterPhone . '%');

                if(!empty($filterMail))
                    $player->orWhere('father_email', 'like', '%' . $filterMail . '%');


                $ids = $player->pluck('id');
            }
            $receipts = Receipts::orderBy('id','desc')
                ->whereIn('branch_id', $branchIds)->where('receipt_type',2);
            if(isset($recipt_id))
                 $receipts->where('id',$recipt_id);

            if(isset($ids)){
                $receipts->whereIn('from', $ids)->where('type_of','players')->get();
            }

                $receipts = $receipts->paginate(10);

            $players =Players::with('PlayerSportPrice')
                ->whereIn('branch_id', $branchIds)
                ->get();
    //        dd($players[0]->PlayerSportPrice->price);
            $receiptTypes= ReceiptTypes::where('is_pay',0)->get();
            return view('Dashboard.Receipts.index',compact('receipts','players','receiptTypes'));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            if(\Auth::user()->hasRole('administrator')){
                $branchIds = Branchs::get()->pluck('id')->toArray();
            }
            else{
                $branchIds = \Auth::user()->branches->pluck('id')->toArray();
            }
            $players =Players::with('PlayerSportPrice')
                ->whereIn('branch_id', $branchIds)
                ->get();
            if(\Auth::user()->hasRole('administrator'))
                $branches = Branchs::get();
            else
                $branches =  \Auth::user()->branches;

    //        dd($players[0]->PlayerSportPrice->price);
            $receiptTypes= ReceiptTypes::whereIn('branch_id',$branchIds)->where('is_pay',0)->get();
            $rents= StadiumsRentTable::whereIn('branch_id',$branchIds)->get();
            $trainers = User::whereHas('branches',function($q) use ($branchIds){
                $q->whereIn('branchs.id',$branchIds);
            })->get();

            return view('Dashboard.Receipts.create',compact('players','rents','trainers','receiptTypes' , 'branches'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \App\Http\Requests\StoreReceiptsRequest  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $priceListId=null;
            $packageId=null;

            if($request->typePrice == "price_list") {
                $priceListId = $request->price_list;
            } else{
                $packageId = $request->price_list;
            }
            if(!is_null($request->from_others)){
                $from = $request->from_others;
            }else{
                $from = $request->from;
            }
            if($request->to == 39){
                $payment_type = 2;
            }elseif(is_null($request->serial)){
                $payment_type = 1;
            }else{
                $payment_type = 3;
            }
            if($request->discount == 'none'){
                $discount_approved = 3;
            }else{
                $discount_approved = 1;
            }
           $receipt =  Receipts::create([
                'user_id'=>auth()->user()->id,
                'type_of'=>$request->from_type,
                'payment_type'=>$payment_type,
                'from'=>$from,
                'to'=>$request->to,
                'type_of_amount'=>$request->type_of_amount,
                'trinar_id'=>$request->traina_id,
                'discount_approved'=>$discount_approved,
                'amount'=>$request->amount,
                'recipt_no'=>$request->recipt_no,
                'paid'=>$request->paid,
                'statement'=>$request->statement,
                'date_receipt'=>$request->date,
                'due_date'=>$request->due_date,
                'price_list_id'=>$priceListId,
                'package_id'=>$packageId,
                'discount_type'=>$request->discount,
                'discount'=>$request->discount_rate,
                'discount_amount_value'=>$request->discount_amount_value,
                'payer'=>$request->payer,
                'branch_id'=>$request->branch_id,
                'serial_number'=>$request->serial,
                'receipt_type'=>2,
            ]);
            if(!is_null($request->rentList)){
               $rent = StadiumsRentTable::find($request->rentList);
               $rent->recipt_id = $receipt->id;
               $rent->update();
            }
            return redirect()->route('receipt.index')->with('message','تم اضافه الايصال بنجاح ');

        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\Models\Receipts  $receipts
         * @return \Illuminate\Http\Response
         */
        public function edit(Receipts $receipt)
        {
            if(\Auth::user()->hasRole('administrator')){
                $branchIds = Branchs::get()->pluck('id')->toArray();
            }
            else{
                $branchIds = \Auth::user()->branches->pluck('id')->toArray();
            }

            $receiptTypes= ReceiptTypes::whereIn('branch_id',$branchIds)->get();
            $players =Players::where('branch_id', $branchIds)
            ->get();
            if(\Auth::user()->hasRole('administrator'))
                $branches = Branchs::get();
            else
                $branches =  \Auth::user()->branches;

            return view('Dashboard.Receipts.edit',compact('players','receipt','receiptTypes','branches'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \App\Http\Requests\UpdateReceiptsRequest  $request
         * @param  \App\Models\Receipts  $receipts
         * @return \Illuminate\Http\Response
         */
        public function update(StoreReceiptsRequest $request, Receipts $receipt)
        {
            $priceListId=null;
            $packageId=null;

            if($request->typePrice == "price_list") {
                $priceListId = $request->price_list;
            } else{
                $packageId = $request->price_list;
            }
            $receipt->user_id=auth()->user()->id;
            $receipt->from=$request->from;
            $receipt->to=$request->to;
            $receipt->type_of=$request->from_type;
            $receipt->type_of_amount=$request->type_of_amount;
            $receipt->amount=$request->amount;
            $receipt->paid=$request->paid;
            $receipt->statement=$request->statement;
            $receipt->price_list_id=$priceListId;
            $receipt->discount_type=$request->discount;
            $receipt->discount=$request->discount_rate;
            $receipt->discount_amount_value=$request->discount_amount_value;
            $receipt->package_id=$packageId;
            $receipt->branch_id=$request->branch_id;
            $receipt->date_receipt=$request->date;
            $receipt->payer=$request->payer;
            $receipt->serial_number = $request->serial;
            $receipt->save();
            return redirect()->route('receipt.index')->with('message','تم تعديل الايصال بنجاح ');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Models\Receipts  $receipts
         * @return \Illuminate\Http\Response
         */
        public function destroy(Receipts $receipt)
        {
            $receipt->delete();
            return redirect()->route('receipt.index')->with('error','تم تعديل الايصال بنجاح ');

        }

        public function getPlayerSportPrice(Request  $request){
    //    $player=      Players::find($request->player_id);
    //        $sport_id =  $player->sport_id;
    //        $level_id =  $player->level_id;
    //          $price_list =  PriceList::where(['sport_id'=>$sport_id, 'level_id'=>$level_id])->get()->first();
            $price= 0;
            if($request->typePrice  =="price_list"){
               $priceList =  PriceList::find($request->id);
                $price = $priceList->price;
            }
             if($request->typePrice=="package"){
                 $package = Packages::find($request->id);
                 $price = $package->manuel_price ? $package->manuel_price : $package->total_package;
             }
            return     \Response::json(['price'=>$price])  ;
        }

        public function filter($request){
            $fromDate = $request->fromDate;
            $toDate = $request->toDate;
            $type = $request->type;
            $Receipts = new Receipts();
            //DB::connection()->enableQueryLog();

    //dd($request->all());
            $Receipts = $Receipts->where('receipt_type',2);

            if(!is_null($toDate)&&!is_null($fromDate))
                $Receipts->whereBetween("$request->type_date", [$fromDate.' 00:00:00', $toDate.' 23:59:59']);
            elseif (!is_null($fromDate))
                $Receipts->whereBetween("$request->type_date", [$fromDate.' 00:00:00', $fromDate.' 23:59:59']);
            elseif (!is_null($toDate))
                $Receipts->whereBetween("$request->type_date", [$toDate.' 00:00:00', $toDate.' 23:59:59']);

            if($type)
                $Receipts = $Receipts->whereHas('receiptType' , function($query) use ($type){
                    $query->where('type',$type);
                });


            if($request->from_others)
                $Receipts = $Receipts->where("from", $request->from_others)->where('type_of',"others");


            if($request->from_player)
                $Receipts = $Receipts->where("from", $request->from_player)->where('type_of',"players");

            if($request->to)
                $Receipts = $Receipts->where("to", $request->to);

            $Receipts = $Receipts->paginate(10);

            //$queries = DB::getQueryLog();*
            return $Receipts;

        }
      public function discount_waiting_approve(){
          $receipts = Receipts::query()->where('discount_approved',1)->get();
          return view('Dashboard.Receipts.discount',compact('receipts'));
      }
       public function discount_approved(Request $request){
          $receipt =  Receipts::query()->find($request->id);
          $receipt->discount_approved = 2;
          $receipt->update();
          return redirect()->route('receipt.discount_waiting_approve');
       }
      public function discount_disapproved(Request $request){
          $receipt =  Receipts::query()->find($request->id);
          $receipt->discount_approved = 0;
          $receipt->update();
          return redirect()->route('receipt.discount_waiting_approve');
      }

        /*
         *  pdf file
         * */

        }
