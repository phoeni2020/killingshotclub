<?php

namespace App\Http\Controllers;

use App\Models\Custody;
use App\Models\CustodyExpense;
use App\Models\Receipts;
use App\Models\ReceiptTypes;
use App\Models\SettlementRequest;
use App\Http\Requests\StoreSettlementRequestRequest;
use App\Http\Requests\UpdateSettlementRequestRequest;
use Carbon\Carbon;

class SettlementRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settlements = SettlementRequest::where('status',0)->paginate(10);
        return view('Dashboard.SettlementRequests.index',compact('settlements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSettlementRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettlementRequestRequest $request)
    {

       SettlementRequest::create([
           'custody_id'=>$request->custody_id,
           'custody_expenses'=>$request->custody_expenses,
           'remain'=>$request->remain,
           'to'=>$request->to,
       ]);

        $Custody=   Custody::find($request->custody_id);
        $Custody->requested = 1;
        $Custody->save();
       return redirect()->route('custody.index')->with('message','لقد تم تقديم طلبك . انتظار موافقه التسويه من قبل المدير');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SettlementRequest  $settlementRequest
     * @return \Illuminate\Http\Response
     0*/
    public function show(SettlementRequest $settlementRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SettlementRequest  $settlementRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(SettlementRequest $settlementRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSettlementRequestRequest  $request
     * @param  \App\Models\SettlementRequest  $settlementRequest
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettlementRequestRequest $request)
    {
       $SettlementRequest = SettlementRequest::query()->where('custody_id',$request->custody_id)->first();
       $SettlementRequest->status = 1;
       $SettlementRequest->date = Carbon::now();
       $SettlementRequest->save();
        $branch = ReceiptTypes::query()->find($SettlementRequest->to)->branch_id;
        $custody = ReceiptTypes::query()->where('branch_id',$branch)->where('type','Custody')->first();
        $custodyExpenses = '';
        if(is_null($custody)){
            $custody = 62;
            $custodyExpenses = CustodyExpense::query()->where('custody_id',$request->custody_id)->get();

        }else{
            $custody = $custody['id'];
            $custodyExpenses = CustodyExpense::query()->where('custody_id',$request->custody_id)->get();
        }
        Receipts::create([
            'user_id'=>auth()->user()->id,
            'payment_type'=>1,
            'from'=>$custody,
            'to'=>$SettlementRequest->to,
            'type_of_amount'=>$request->type_of_amount,
            'amount'=>$SettlementRequest->remain,
            'statement'=>'تسوية عهده',
            'branch_id'=>$branch,
            'recipt_no'=>0,
            'date_receipt'=>Carbon::now(),
            'type_of'=>'others',
            'receipt_type'=>2,
        ]);
        foreach ($custodyExpenses as $custodyExpens){
            Receipts::create([
                'user_id'=>auth()->user()->id,
                'payment_type'=>1,
                'from'=>$SettlementRequest->to,
                'to'=>$custody,
                'amount'=>$custodyExpens->price,
                'statement'=>'عهده',
                'branch_id'=>$branch,
                'recipt_no'=>0,
                'date_receipt'=>Carbon::now(),
                'type_of'=>'others',
                'receipt_type'=>1,
            ]);
        }

       return response()->json(['msg'=>'done',200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SettlementRequest  $settlementRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettlementRequest $settlementRequest)
    {
        //
    }
}
