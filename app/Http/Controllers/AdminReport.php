<?php

namespace App\Http\Controllers;

use App\Exports\ExportToExcelSheet;
use App\Models\AttendancePlayers;
use App\Models\Branchs;
use App\Models\Custody;
use App\Models\EmployeeBranch;
use App\Models\Players;
use App\Models\PriceList;
use App\Models\Receipts;
use App\Models\ReceiptsPay;
use App\Models\ReceiptTypes;
use App\Models\SettlementRequest;
use App\Models\Sports;
use App\Models\Stadium;
use App\Models\StadiumRentCancellations;
use App\Models\StadiumsRentTable;
use App\Models\Tournaments;
use App\Models\TournamentSubscriptions;
use App\Models\TrainerAndPlayer;
use App\Models\TrainerAttendance;
use App\Models\User;
use App\Services\PDF\ConvertDataToPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscription_reports(Request $request)
    {
        //dd('tst');
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');
        // Add more filter parameters as needed

        $trainer_players = TrainerAndPlayer::query()
            ->whereIn('branch_id',$branchIds)
            ->orderBy('id', 'DESC');
        if ($player) {
            $trainer_players->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
        }
        if ($trainer) {
            $trainer_players->where('trainer_id',$trainer);
        }
        if ($branch) {
            $trainer_players->where('branch_id', $branch);
        }
        if ($startDate && $endDate) {
            $trainer_players->whereBetween('date', [$startDate, $endDate]);
        }
        // Add more filter conditions for other parameters
        $trainers = User::where('is_trainer','1')
            ->whereHas('branches',function ($q) use ($branchIds) {
                $q->whereIn('branchs.id',$branchIds);
            })
            ->get();
        $players = Players::whereIn('branch_id', $branchIds)->get();
        // Fetch the filtered report data
        $reportsData = $trainer_players->paginate(25);
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;
        // Return the report view with the filtered data
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.subcription",$reportsData," تقرير الاشتراكات");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($reportsData ,'Dashboard.reports.pdf.subcription');
                return Excel::download($ExportToExcelSheet , ' تقرير الاشتراكات.xlsx');
            }
        }

        return view('Dashboard.reports.subscription_reports',
            compact('reportsData', 'branches',
            'trainers','players'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function income_list(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list')) {

        }
        //dd('tst');
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $price_list = PriceList::select(['id','sport_id'])->get()->toArray();
        $price_lists = [];
        $sport_id = 0;
        foreach ($price_list as $item) {
            if($sport_id != $item['sport_id'])
                $sport_id = $item['sport_id'];
            $price_lists[$item['sport_id']][] = $item['id'];
        }
        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $safe = $request->input('safe_id');
        $sport_id = $request->input('sport_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');

        $subscriptions = Receipts::where('receipt_type',2)->where('type_of','players');//->sum('amount');

        $otherIncome = Receipts::where('receipt_type',2)->whereNotIn('type_of',['players']);//->sum('amount');

        $rentAndMaintance = Receipts::where('receipt_type',1)->whereNotIn('type_of',['players'])->whereIn('to',[2,3,4]);//->sum('amount');

        $playerExpense = Receipts::where('receipt_type',1)->where('type_of','players')->whereIn('to',[2,3,4]);//->sum('amount');

        $otherExpense = Receipts::where('receipt_type',1)->whereNotIn('to',[2,3,4]);//->get();

        if ($player) {
            $subscriptions->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
            $otherIncome->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
            $otherExpense->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
            $rentAndMaintance->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
            $playerExpense->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
        }
        if ($trainer) {
            $subscriptions->where('trainer_id',$trainer);
            $otherIncome->where('trainer_id',$trainer);
            $rentAndMaintance->where('trainer_id',$trainer);
            $otherExpense->where('trainer_id',$trainer);
            $playerExpense->where('trainer_id',$trainer);
        }
        if ($branch) {
            $subscriptions->where('branch_id', $branch);
            $otherIncome->where('branch_id', $branch);
            $rentAndMaintance->where('branch_id', $branch);
            $otherExpense->where('branch_id', $branch);
            $playerExpense->where('branch_id', $branch);
        }
        if ($safe) {
            $subscriptions->where('from', $safe)->orWhere('to', $safe);
            $otherIncome->where('from', $safe)->orWhere('to', $safe);
            $rentAndMaintance->where('from', $safe)->orWhere('to', $safe);
            $otherExpense->where('from', $safe)->orWhere('to', $safe);
            $playerExpense->where('from', $safe)->orWhere('to', $safe);
        }
        if ($startDate && $endDate) {
            $subscriptions->whereBetween('date_receipt', [$startDate, $endDate]);
            $otherIncome->whereBetween('date_receipt', [$startDate, $endDate]);
            $rentAndMaintance->whereBetween('date_receipt', [$startDate, $endDate]);
            $otherExpense->whereBetween('date_receipt', [$startDate, $endDate]);
            $playerExpense->whereBetween('date_receipt', [$startDate, $endDate]);
        }
        if ($sport_id) {
            $key = isset($price_lists[$request->sport_id]) ?$price_lists[$request->sport_id] : [];
            $subscriptions->whereIn('price_list_id', $key);
        }
        // Add more filter conditions for other parameters
        $trainers = User::where('is_trainer','1')
            ->whereHas('branches',function ($q) use ($branchIds) {
                $q->whereIn('branchs.id',$branchIds);
            })
            ->get();

        $players = Players::whereIn('branch_id', $branchIds)->get();
        $safes = ReceiptTypes::where('type', 'Save_money')->get();

        $sports = Sports::all();
        // Fetch the filtered report data

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        $subscriptionsSum = $subscriptions->sum('amount');
        $otherIncome = $otherIncome->sum('amount');
        $rentAndMaintance = $rentAndMaintance->sum('amount');
        $otherExpense = $otherExpense->sum('amount');
        $playerExpense = $playerExpense->sum('amount');
        $otherExpense += $playerExpense;

//        dd($queries);
        $total = $subscriptionsSum + $otherIncome + $rentAndMaintance + $otherExpense;
        if ($request->filter){
            $data = ['subscriptionsSum'=>$subscriptionsSum,'otherIncome'=>$otherIncome,
                'rentAndMaintance'=>$rentAndMaintance,'otherExpense'=>$otherExpense,'playerExpense'=>$playerExpense,'total'=>$total];
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.income_reports",$data," تقرير الاشتراكات");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($data ,'Dashboard.reports.pdf.income_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير الاشتراكات.xlsx');
            }
        }
        // Return the report view with the filtered data
        return view('Dashboard.reports.income_reports',
            compact( 'branches',
                'trainers','safes','players','sports','total','otherIncome','subscriptionsSum','rentAndMaintance','otherExpense'));
    }


    public function recipt_report(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list')) {

        }
        //dd('tst');
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        }
        else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $price_list = PriceList::select(['id','sport_id'])->get()->toArray();
        $price_lists = [];
        $sport_id = 0;
        foreach ($price_list as $item) {
            if($sport_id != $item['sport_id'])
                $sport_id = $item['sport_id'];
            $price_lists[$item['sport_id']][] = $item['id'];
        }

        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $sport_id = $request->input('sport_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $safe = $request->input('safe_id');
        $recipt_id = $request->input('recipt_id');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');
        $type = $request->input('type');
        $type_income = $request->input('type_income');
        $payment_type = $request->input('payment_type');
        // Add more filter parameters as needed

        $receipts = Receipts::query();//->sum('amount');
        if ($player) {
            $receipts->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
        }

        if ($trainer) {
            $receipts->where('trainer_id',$trainer);
        }

        if ($branch) {
            $receipts->where('branch_id', $branch);
        }

        if ($startDate && $endDate) {
            $receipts->whereBetween('date_receipt', [$startDate, $endDate]);
        }

        if ($sport_id) {
            $key = isset($price_lists[$request->sport_id]) ?$price_lists[$request->sport_id] : [];
            $receipts->whereIn('price_list_id', $key);
        }
        if ($safe) {
            $receipts->where('from', $safe)->orWhere('to', $safe);
        }
        if ($recipt_id) {
            $receipts->where('id', $recipt_id);
        }
        if($type)
            $Receipts = $receipts->whereHas('receiptType' , function($query) use ($type){
                $query->where('type',$type);
        });

        if($type_income){
            $receipts->where('receipt_type', $type_income);
        }
        if(!empty($payment_type)){
            $receipts->where('payment_type', $payment_type);
        }

        // Add more filter conditions for other parameters

        $trainers = User::where('is_trainer','1')
            ->whereHas('branches',function ($q) use ($branchIds) {
                $q->whereIn('branchs.id',$branchIds);
            })
            ->get();

        $players = Players::whereIn('branch_id', $branchIds)->get();

        $sports = Sports::all();
        // Fetch the filtered report data

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.safe_reports", $receipts->orderBy('id','desc')->get(),"تقرير الخزنه.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet( $receipts->orderBy('id','desc')->get(),'Dashboard.reports.pdf.safe_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير الخزنه.xlsx');
            }
        }
        $receipts = $receipts->orderBy('id','desc')->get();
//        dd($queries);
        $receiptTypes= ReceiptTypes::query()->get();

        $safes = ReceiptTypes::where('type', 'Save_money')->get();
        // Return the report view with the filtered data

        return view('Dashboard.reports.safe_reports',
            compact( 'branches',
                'trainers','players','safes','receiptTypes','sports','receipts'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedules_reports(Request $request)
    {
        //
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        //$search_keyword = $request->input*('search_keyword');
        // Add more filter parameters as needed

        $stadiums_tent_table = StadiumsRentTable::query()
            ->whereHas('stadiums',function ($q) use ($branchIds) {
                $q->whereIn('branch_id',$branchIds);
            })
            ->orderBy('id', 'DESC');
        if(!empty($request->name)){
            $stadiums_tent_table->where('name', 'like', '%' .$request->name . '%');
        }
        if ($branch) {
            $stadiums_tent_table->whereHas('stadiums', function ($q) use ($branch) {
                $q->where('branch_id', $branch);
            });
        }

        if ($startDate && $endDate) {
            $stadiums_tent_table->where('date','>=', $startDate)
                ->where('date','<=', $endDate);
        }
        // Add more filter conditions for other parameters
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;
        // Fetch the filtered report data
        $reportsData = $stadiums_tent_table->paginate(25)->groupBy('day');
        return view('Dashboard.reports.schedules_reports', compact('reportsData', 'branches'));

    }

    public function stadiums_reports(Request $request)
    {
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $branch = $request->input('branch_id');
        // Get all times for every stadium on every day
        $stadiums = Stadium::whereIn('branch_id', $branchIds)->get();
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');

        $new = collect([]);
        $reports = StadiumsRentTable::orderBy('stadium_id')
            ->whereHas('stadiums', function ($q) use ($branchIds) {
            $q->whereIn('branch_id', $branchIds);
        });
        if(!empty($request->name)){
            $reports->where('name', 'like', '%' .$request->name . '%');
        }
        $reports2 = TrainerAndPlayer::orderBy('stadium_id')
            ->whereHas('stadiums', function ($q) use ($branchIds) {
                $q->whereIn('branch_id', $branchIds);
            });
        if ($request->stadium) {
            $reports->where('stadium_id', $request->stadium);
            $reports2->where('stadium_id', $request->stadium);
        }
        if ($branch) {
            $reports->whereHas('stadiums', function ($q) use ($branch) {
                $q->where('branch_id', $branch);
            });
            $reports2->whereHas('stadiums', function ($q) use ($branch) {
                $q->where('branch_id', $branch);
            });
        }
        if ($startDate) {
            $reports->whereDate('time_from', $startDate);

            $reports2->whereDate('time_from', $startDate);
        }

        $reports = $new->merge($reports->get())->merge($reports2->get());

        $reports = $reports;

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.rents_reports",['reports'=>$reports]," تقرير التحليل المالي.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet(['reports'=>$reports] ,'Dashboard.reports.pdf.rents_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير التحليل المالي.xlsx');
            }
        }
        return view('Dashboard.reports.stadiums_reports',
            compact('reports', 'branches', 'stadiums'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscription_income_reports(Request $request)
    {
        //dd('tst');
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');
        // Add more filter parameters as needed

        $trainer_players = TrainerAndPlayer::query()
            ->whereIn('branch_id',$branchIds)
            ->orderBy('id', 'DESC')->with('players');
        if ($player) {
            $trainer_players->whereHas('players', function ($q) use ($player) {
                $q->where('player_id', $player);
            });
        }
        if ($trainer) {
            $trainer_players->where('trainer_id',$trainer);
        }
        if ($branch) {
            $trainer_players->where('branch_id', $branch);
        }
        if ($startDate && $endDate) {
            $trainer_players->whereBetween('date', [$startDate, $endDate]);
        }
        // Add more filter conditions for other parameters
        $trainers = User::where('is_trainer','1')
            ->whereHas('branches',function ($q) use ($branchIds) {
                $q->whereIn('branchs.id',$branchIds);
            })
            ->get();
        $players = Players::whereIn('branch_id', $branchIds)->get();
        // Fetch the filtered report data
        $reportsData = $trainer_players->paginate(25);
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;


        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.rents_reports",['reportsData'=>$reportsData]," تقرير التحليل المالي.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet(['reportsData'=>$reportsData] ,'Dashboard.reports.pdf.rents_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير التحليل المالي.xlsx');
            }
        }
        // Return the report view with the filtered data
        return view('Dashboard.reports.subscription_income_reports',
            compact('reportsData', 'branches',
                'trainers','players'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function income_reports_month(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list_month')) {

        }
        //dd('tst');
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $price_list = PriceList::select(['id', 'sport_id'])->get()->toArray();
        $price_lists = [];
        $sport_id = 0;
        foreach ($price_list as $item) {
            if ($sport_id != $item['sport_id'])
                $sport_id = $item['sport_id'];
            $price_lists[$item['sport_id']][] = $item['id'];
        }
        if(!empty($request->input('fromDate'))){
            $startDate = $request->input('fromDate');

            $month = explode('-',$startDate)[1];
        }

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        $branchs = [];
        $barnchSport = [];
        foreach ($branches as $branch) {
            $sportsResulat = DB::select('SELECT `sport_id` FROM `branches_sports` WHERE `branch_id`=' . $branch->id);
            foreach ($sportsResulat as $sport) {
                $sport = Sports::query()->where('id', $sport->sport_id)->first();
                $price_lists = PriceList::query()->select(['id'])->where('sport_id', $sport->id)->pluck('id')->toArray();
                $barnchSport[$branch->id][$sport->id]['sport_name'] = $sport->name;
                $barnchSport[$branch->id][$sport->id]['sport_id'] = $sport->id;
                $barnchSport[$branch->id][$sport->id]['price_list'] = $price_lists;
                $barnchSport[$branch->id][$sport->id]['branch_name'] = $branch->name;
            }
        }
        $branchesSports = [];
        foreach ($barnchSport as $id => $value) {
            $sportArr = array_pop($value);

            $subscriptions = Receipts::where('receipt_type', 2)
                ->where('type_of', 'players')->where('branch_id', $id)
                ->whereIn('price_list_id', $sportArr['price_list']);

            $otherIncome = Receipts::where('receipt_type', 2)->whereNotIn('type_of', ['players']);

            $rentAndMaintance = Receipts::where('receipt_type', 1)->whereNotIn('type_of', ['players'])
                ->whereIn('to', [2, 3, 4])->where('branch_id', $id);

            $playerExpense = Receipts::where('receipt_type', 1)->where('type_of', 'players')
                ->whereIn('to', [2, 3, 4])->where('branch_id', $id);

            $otherExpense = Receipts::where('receipt_type', 1)->whereNotIn('to', [2, 3, 4])->where('branch_id', $id);

            $salary = Receipts::where('receipt_type', 1)->where('to', 54)->where('branch_id', $id);

            $public_expnse = Receipts::where('receipt_type', 1)->where('to', 55)->where('branch_id', $id);

            $public_salary = Receipts::where('receipt_type', 1)->where('to', 56)->where('branch_id', $id);

            if(isset($startDate)){
                $amount = $subscriptions->whereMonth('date_receipt', '=', $month)->sum('amount');
                $otherIncome = $otherIncome->whereMonth('date_receipt', '=', $month)->sum('amount');
                $rentAndMaintance = $rentAndMaintance->whereMonth('date_receipt', '=', $month)->sum('amount');
                $playerExpense = $playerExpense->whereMonth('date_receipt', '=', $month)->sum('amount');
                $otherExpense = $otherExpense->whereMonth('date_receipt', '=', $month)->sum('amount');
                $salary = $salary->whereMonth('date_receipt', '=', $month)->sum('amount');
                $public_expnse = $public_expnse->whereMonth('date_receipt', '=', $month)->sum('amount');
                $public_salary = $public_salary->whereMonth('date_receipt', '=', $month)->sum('amount');
            }else{
                $amount = $subscriptions->sum('amount');

                $otherIncome = Receipts::where('receipt_type', 2)->whereNotIn('type_of', ['players'])->sum('amount');

                $rentAndMaintance = Receipts::where('receipt_type', 1)->whereNotIn('type_of', ['players'])
                    ->whereIn('to', [2, 3, 4])->where('branch_id', $id)->sum('amount');

                $playerExpense = Receipts::where('receipt_type', 1)->where('type_of', 'players')
                    ->whereIn('to', [2, 3, 4])->where('branch_id', $id)->sum('amount');

                $otherExpense = Receipts::where('receipt_type', 1)->whereNotIn('to', [2, 3, 4])->where('branch_id', $id)->sum('amount');

                $salary = Receipts::where('receipt_type', 1)->where('to', 54)->where('branch_id', $id)->sum('amount');

                $public_expnse = Receipts::where('receipt_type', 1)->where('to', 55)->where('branch_id', $id)->sum('amount');

                $public_salary = Receipts::where('receipt_type', 1)->where('to', 56)->where('branch_id', $id)->sum('amount');
            }
            $branchesSports[$id][] = [
                'subscription' => $amount,
                'otherIncome' => $otherIncome,
                'totalIncome' => $otherIncome + $amount,
                'rentAndMaintance' => $rentAndMaintance,
                'expense' => $playerExpense + $otherExpense,
                'salary' => -$salary,
                'totalExpense' => $rentAndMaintance + $playerExpense + $otherExpense - $salary,
                'clearIncome' => ($otherIncome + $amount)+($rentAndMaintance + $playerExpense + $otherExpense - $salary),
                'public' => $rentAndMaintance + $playerExpense + $otherExpense - $salary,
                'public_expnse' =>$public_expnse,
                'public_salary' =>$public_salary,
                'branch' => $sportArr['branch_name'],
                'sport_name' => $sportArr['sport_name']
            ];
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.income_month_reports",$branchesSports,"قائمة الدخل عن شهر معين.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($branchesSports ,'Dashboard.reports.pdf.income_month_reports');
                return Excel::download($ExportToExcelSheet , ' قائمة الدخل عن شهر معين.xlsx');
            }
        }
        return view('Dashboard.reports.income_month_reports',
            compact('branches', 'branchesSports',));
    }

    public function income_reports_comparison(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list_month')) {

        }

        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }

        $price_list = PriceList::select(['id', 'sport_id'])->get()->toArray();
        $price_lists = [];
        $sport_id = 0;

        $startDate = $request->fromDate;
        $endDate = $request->toDate;
        $months = [];
        if(!empty($startDate)&&!empty($endDate)){
            $months = $this->getMonthListFromDate($startDate,$endDate);
        }
        $monthsParse = [];
        foreach ($months as $month){
            $monthsParse[] =  date_parse($month);
        }
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        $branchsRecive = [];
            foreach ($branches as $branch) {

                foreach ($monthsParse as $monthParse) {
                    $recipts = Receipts::query()->where('branch_id', $branch->id)->whereMonth('due_date', $monthParse['month'])->where('receipt_type', 2)->sum('amount');
                    $reciptsPay = Receipts::query()->where('branch_id', $branch->id)->whereMonth('due_date', $monthParse['month'])->where('receipt_type', 1)->sum('amount');

                    $branchsRecive[$branch->id][$monthParse['month']] = $recipts;
                    $branchsPay[$branch->id][$monthParse['month']] = $reciptsPay;
                    $branchsClear[$branch->id][$monthParse['month']] = $recipts + $reciptsPay;
                    $branchsClear[$branch->id][$monthParse['month']] = $recipts + $reciptsPay;

                }
                $branchsRecive[$branch->id]['name'] = $branch->name;
                $branchsPay[$branch->id]['name'] = $branch->name;
                $branchsClear[$branch->id]['name'] = $branch->name;


            }
        $branchesSports = [];
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.comparison",['months'=>$months,'branchsRecive'=>$branchsRecive,
                    'branchsClear'=>$branchsClear,'branchsPay'=>$branchsPay,'branchesSports'=>$branchesSports]," تقرير المقارنه.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet(['months'=>$months,'branchsRecive'=>$branchsRecive,
                    'branchsClear'=>$branchsClear,'branchsPay'=>$branchsPay,'branchesSports'=>$branchesSports] ,
                    'Dashboard.reports.pdf.comparison');
                return Excel::download($ExportToExcelSheet , ' تقريرالمقارنه.xlsx');
            }
        }
        return view('Dashboard.reports.comparison',
            compact('branches','months','branchsRecive','branchsClear','branchsPay','branchesSports',));
    }

    public function expenseAnalysis(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list_month')) {

        }

        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        $barnchSport = [];
        foreach ($branches as $branch) {
            $sportsResulat = DB::select('SELECT `sport_id` FROM `branches_sports` WHERE `branch_id`=' . $branch->id);
            foreach ($sportsResulat as $sport) {
                $barnchSport[$branch->id]['branch_name'] = $branch->name;
            }
        }
        $months = [
            1=>'يناير',
            2=>'فبراير',
            3=>'مارس',
            4=>'ابريل',
            5=>'مايو',
            6=>'يونيو',
            7=>'يوليو',
            8=>'اغسطس',
            9=>'سبتمبر',
            10=>'اكتوبر',
            11=>'نوفمبر',
            12=>'ديسمبر'
        ];
        $branchesMonth = [];
        foreach ($months as $month => $value) {
            foreach ($barnchSport as $id => $sportArr) {
                $rentAndMaintance = Receipts::where('receipt_type', 1)->whereNotIn('type_of', ['players'])
                    ->whereIn('to', [2, 3, 4]);

                $playerExpense = Receipts::where('receipt_type', 1)->where('type_of', 'players')
                    ->whereIn('to', [2, 3, 4]);

                $otherExpense = Receipts::where('receipt_type', 1)->whereNotIn('to', [2, 3, 4]);

                $salary = Receipts::where('receipt_type', 1)->where('to', 54);

                $public_expnse = Receipts::where('receipt_type', 1)->where('to', 55);

                $public_salary = Receipts::where('receipt_type', 1)->where('to', 56);

                $rentAndMaintance = $rentAndMaintance->whereMonth('date_receipt', '=', $month)->sum('amount');
                $playerExpense = $playerExpense->whereMonth('date_receipt', '=', $month)->sum('amount');
                $otherExpense = $otherExpense->whereMonth('date_receipt', '=', $month)->sum('amount');
                $salary = $salary->whereMonth('date_receipt', '=', $month)->sum('amount');
                $public_expnse = $public_expnse->whereMonth('date_receipt', '=', $month)->sum('amount');
                $public_salary = $public_salary->whereMonth('date_receipt', '=', $month)->sum('amount');

                $branchesSports[$month] = [
                    'rentAndMaintance' => $rentAndMaintance,
                    'expense' => $playerExpense + $otherExpense,
                    'salary' => -$salary,
                    'totalExpense' => $rentAndMaintance - $playerExpense  -$otherExpense - $salary,
                    'public' => $rentAndMaintance + $playerExpense + $otherExpense - $salary,
                    'public_expnse' =>$public_expnse,
                    'public_salary' =>$public_salary,
                ];
            }
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.expanse_analysis_reports",
                    ['months'=>$months,'branchesSports'=>$branchesSports]," تقرير التحليل المالي.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet(['months'=>$months,'branchesSports'=>$branchesSports] ,
                    'Dashboard.reports.pdf.expanse_analysis_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير التحليل المالي.xlsx');
            }
        }
        return view('Dashboard.reports.expanse_analysis_reports',
            compact('branches', 'branchesSports','months'));
    }

    public function rent_report(Request $request)
    {
        if (\Auth::user()->hasRole('administrator') || auth()->user()->hasPermission('income_list_month')) {

        }
        $staduims = Stadium::all();
        $staduimsInfo = [];
        foreach ($staduims as $staduim){
            $rentStadumInfoQuery = StadiumsRentTable::query()->where('stadium_id',$staduim->id);
            if(!empty($request->name)){
                $rentStadumInfoQuery->where('name', 'like', '%' .$request->name . '%');
            }
            $canceltionRentStadumInfoQuery = StadiumRentCancellations::query()->where('stadium_id',$staduim->id);
            $info = $rentStadumInfoQuery->first();
            if(is_null($info)){
                $staduimsInfo[$staduim->id][] = $staduim;
            }
            else
            {
                $staduimsInfo[$staduim->id][] = $staduim;
                $amount = $rentStadumInfoQuery->sum('price');
                $rentTimes = $rentStadumInfoQuery->count('stadium_id');
                $canceltionRentTimes = $canceltionRentStadumInfoQuery->count('stadium_id');
                $staduimsInfo[$staduim->id]['total'] = $amount;
                $staduimsInfo[$staduim->id]['rent_times'] = $rentTimes;
                $staduimsInfo[$staduim->id]['canceltion_rent_times'] = $canceltionRentTimes;
            }
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.rents_reports",$staduimsInfo," تقرير الايجار.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($staduimsInfo ,'Dashboard.reports.pdf.rents_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير الايجار.xlsx');
            }
        }
        return view('Dashboard.reports.rents_reports',
            compact('staduimsInfo'));
    }

    public function stadiums_reports_detial(Request $request)
    {
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        // Retrieve and process filter parameters from the request
        $branch = $request->input('branch_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        //$search_keyword = $request->input('search_keyword');
        // Add more filter parameters as needed

        $stadiums_tent_table = StadiumsRentTable::query()
            ->whereHas('stadiums',function ($q) use ($branchIds) {
                $q->whereIn('branch_id',$branchIds);
            })
            ->orderBy('id', 'DESC');


        if ($branch) {
            $stadiums_tent_table->whereHas('stadiums', function ($q) use ($branch) {
                $q->where('branch_id', $branch);
            });
        }

        if ($startDate && $endDate) {
            $stadiums_tent_table->where('date','>=', $startDate)
                ->where('date','<=', $endDate);
        }
        // Add more filter conditions for other parameters
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;
        // Fetch the filtered report data
        if(!empty($request->name)){
            $stadiums_tent_table->where('name', 'like', '%' .$request->name . '%');
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.rent_detial_reports",$stadiums_tent_table->get()->groupBy('day') ," تقرير الايجار.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($stadiums_tent_table->get()->groupBy('day') ,'Dashboard.reports.pdf.rent_detial_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير الايجار.xlsx');
            }
        }
        $reportsData = $stadiums_tent_table->paginate(25)->groupBy('day');

        return view('Dashboard.reports.rent_detial_reports', compact('reportsData','branches'));

    }

    public function due_date_reports(Request $request)
    {
        if (\Auth::user()->hasRole('administrator')) {
            $branchIds = Branchs::get()->pluck('id')->toArray();
        } else {
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $branch = $request->input('branch_id');
        $sport_id = $request->input('sport_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $safe = $request->input('safe_id');
        $recipt_id = $request->input('recipt_id');

        $type = $request->input('type');
        $type_income = $request->input('type_income');
        // Add more filter parameters as needed

        $receipts = Receipts::query()->whereDate('due_date','>',date('Y-m-d'));//->sum('amount');
        if ($branch) {
            $receipts->where('branch_id', $branch);
        }

        if ($startDate && $endDate) {
            $receipts->whereBetween('date_receipt', [$startDate, $endDate]);
        }

        if ($safe) {
            $receipts->where('from', $safe)->orWhere('to', $safe);
        }

        if ($recipt_id) {
            $receipts->where('id', $recipt_id);
        }

        if($type)
            $receipts->whereHas('receiptType' , function($query) use ($type){
                $query->where('type',$type);
            });

        if($type_income){
            $receipts->where('receipt_type', $type_income);
        }
        // Add more filter conditions for other parameters
        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches = \Auth::user()->branches;

        $receiptTypes= ReceiptTypes::query()->get();

        $safes = ReceiptTypes::where('type', 'Save_money')->get();
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.due_date_reports",$receipts->get()," تقرير الحساب المستحق.pdf");
            }
            if($request->excel){
                //due_date_reports.blade.php
                $ExportToExcelSheet  = new ExportToExcelSheet($receipts->get() ,'Dashboard.reports.pdf.due_date_reports');
                return Excel::download($ExportToExcelSheet , 'تقرير الحساب المستحق.xlsx');
            }
        }
        $receipts = $receipts->get();
        // Fetch the filtered report data
        return view('Dashboard.reports.due_date_reports', compact('receipts','safes','receiptTypes','branches'));
    }
    public function tournament_reports(Request $request)
    {
        $tournaments = TournamentSubscriptions::with('tournament')->with('players')->get();
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.tournment",$tournaments," تقرير المسابقات.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($tournaments ,'Dashboard.reports.pdf.tournment');
                return Excel::download($ExportToExcelSheet , ' تقرير المسابقات.xlsx');
            }
        }
        //        dd($tournaments);
        return view('Dashboard.reports.tournament_report',compact('tournaments'));

    }

    /**
     * @param $dateStart
     * @param $dateEnd
     * @return array
     * @throws \Exception
     */
    public function getMonthListFromDate( $dateStart, $dateEnd)
    {
        $dateStart = Carbon::createFromFormat('Y-m-d',  $dateStart);
        $dateEnd = Carbon::createFromFormat('Y-m-d',  $dateEnd);

        $start    = new \DateTime($dateStart->toDateTimeString()); // Today date
        $end      = new \DateTime($dateEnd->toDateTimeString()); // Create a datetime object from your Carbon object
        $interval = \DateInterval::createFromDateString('1 month'); // 1 month interval
        $period   = new \DatePeriod($start, $interval, $end); // Get a set of date beetween the 2 period

        $months = array();

        foreach ($period as $dt) {
            $months[] = $dt->format("F Y");
        }

        return $months;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function custody_reports(Request $request)
    {
        $branch = $request->input('branch_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');

        $settlements = Custody::query()->with('receipt_pay','receipt_pay.receiptType','receipt_pay.receiptType.branches');//
        if ($branch) {
            $settlements->where('receipt.branch_id', $branch);
        }
        if ($startDate && $endDate) {
            $settlements->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.custody",$settlements->get()," تقرير العهد.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($settlements->get() ,'Dashboard.reports.pdf.custody');
                return Excel::download($ExportToExcelSheet , ' تقرير العهد.xlsx');
            }
        }
        $settlements = $settlements->paginate(10);
        return view('Dashboard.reports.custody_report',compact('settlements'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function income_reports_daily(Request $request)
    {

        if (\Auth::user()->hasRole('administrator'))
            $branches = Branchs::query()->pluck('id')->toArray();
        else
            $branches = \Auth::user()->branches;


        $startDate  =$request->input('fromDate');
        $barnchSport = [];
        $safes = ReceiptTypes::query()->where('type','Save_money')->where('is_rent','0')->whereIn('branch_id',$branches)->get();
        foreach ($safes as $safe) {
            $branch = Branchs::query()->find($safe->branch_id);
            $sportsResulat = DB::select('SELECT `sport_id` FROM `branches_sports` WHERE `branch_id`=' . $branch->id);
            foreach ($sportsResulat as $sport) {
                $sport = Sports::query()->where('id', $sport->sport_id)->first();
                $price_lists = PriceList::query()->select(['id'])->where('sport_id', $sport->id)->pluck('id')->toArray();
                $barnchSport[$safe->id][$sport->id]['safe_name'] = $safe->name;
                $barnchSport[$safe->id][$sport->id]['sport_name'] = $sport->name;
                $barnchSport[$safe->id][$sport->id]['sport_id'] = $sport->id;
                $barnchSport[$safe->id][$sport->id]['price_list'] = $price_lists;
                $barnchSport[$safe->id][$sport->id]['branch_name'] = $branch->name;
            }
        }
        $branchesSports = [];
        foreach ($barnchSport as $id => $value) {
            $sportArr = array_pop($value);
            if(isset($startDate)){
                $income = Receipts::where('receipt_type', 2)->where('to', $id)->whereDate('date_receipt', '=', $startDate)->sum('amount');
                $expense = Receipts::where('receipt_type', 1)->where('to', $id)->whereDate('date_receipt', '=', $startDate)->sum('amount');

            }else{
                $income = Receipts::where('receipt_type', 2)->where('to', $id)->sum('amount');
                $expense = Receipts::where('receipt_type', 1)->where('to', $id)->sum('amount');
                //dd($income,$expense);
            }
            $branchesSports[$id][] = [
                'branch' => $sportArr['branch_name'],
                'safe_name' => $sportArr['safe_name'],
                'sport_name' => $sportArr['sport_name'],
                'income' => $income,
                'expense' => $expense,
            ];
        }
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.income_daily_reports",$branchesSports," تقرير يومي.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($branchesSports,'Dashboard.reports.pdf.income_daily_reports');
                return Excel::download($ExportToExcelSheet , ' تقرير يومي.xlsx');
            }
        }
        $branches = Branchs::query()->whereIn('id',$branches);
        return view('Dashboard.reports.income_daily_reports',
            compact('branches', 'branchesSports',));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleted_recipt(Request $request)
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
        $receipts = Receipts::onlyTrashed()->orderBy('id','desc');
        if(isset($recipt_id))
            $receipts->where('id',$recipt_id);

        if(isset($ids)){
            $receipts->whereIn('from', $ids)->where('type_of','players')->get();
        }
        if(isset($receiptType)){
            $receipts->where('receipt_type',$receiptType);
        }

        $receipts = $receipts->paginate(10);

        $players =Players::with('PlayerSportPrice')
            ->whereIn('branch_id', $branchIds)
            ->get();
        //        dd($players[0]->PlayerSportPrice->price);
        $receiptTypes= ReceiptTypes::where('is_pay',0)->get();
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.deleted_recipt",$receipts," تقرير الفواتير الملغيه.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($receipts,'Dashboard.reports.pdf.deleted_recipt');
                return Excel::download($ExportToExcelSheet , ' تقرير الفواتير الملغيه.xlsx');
            }
        }
        return view('Dashboard.reports.deleted_recipt',compact('receipts','players','receiptTypes'));
    }
    public function attendance_recipt(Request $request)
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
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $user = User::query();

        if(!empty($filterName)||!empty($filterMail) || !empty($filterPhone)){

            if(!empty($filterName))
                $user->orWhere('name', 'like', '%' .$filterName . '%');

            if(!empty($filterMail))
                $user->orWhere('email', 'like', '%' . $filterMail . '%');

            $ids = $user->pluck('id');
        }
        $employees = EmployeeBranch::query()->whereIn('branch_id',$branchIds)->pluck('employee_id')->unique()->toArray();
        $employees = $user->whereIn('id',$employees);
        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.attendance",$employees->get()->toArray()," تقرير الفواتير الملغيه.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($employees->get()->toArray(),'Dashboard.reports.pdf.attendance');
                return Excel::download($ExportToExcelSheet , ' تقرير الحضور.xlsx');
            }
        }
        $employees = $employees->paginate(10);
        return view('Dashboard.reports.attendance',compact('employees','fromDate','toDate'));
    }

    public function attendance_player(Request $request)
    {
        if(\Auth::user()->hasRole('administrator')){
            $branchIds = Branchs::get()->pluck('id')->toArray();
        }
        else{
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }

        $filterName = $request->name;
        $filterMail = $request->email;
        $filterPhone = $request->phone;

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $player = Players::query();

        if(!empty($filterName)||!empty($filterMail) || !empty($filterPhone)){
            if(!empty($filterName))
                $player->orWhere('name', 'like', '%' .$filterName . '%');

            if(!empty($filterPhone))
                $player->orWhere('father_phone', 'like', '%' .$filterPhone . '%');

            if(!empty($filterMail))
                $player->orWhere('father_email', 'like', '%' . $filterMail . '%');

        }
        $player = $player->pluck('id')->toArray();
        if((!is_null($fromDate)&&!is_null($toDate))&&(!empty($fromDate)&&!empty($toDate))){
            $players = AttendancePlayers::query()->whereIn('player_id',$player)->whereBetween('created_at',[$fromDate,$toDate]);
        }else{
            $players = AttendancePlayers::query()->whereIn('player_id',$player);
        }


        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.attendance_players",$players->get()->toArray()," تقرير حضور و انصراف اللاعبين.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($players->get()->toArray(),'Dashboard.reports.pdf.attendance_players');
                return Excel::download($ExportToExcelSheet , ' تقرير الحضور.xlsx');
            }
        }
        $players = $players->paginate(10);
        return view('Dashboard.reports.player_attendance',compact('players','fromDate','toDate'));
    }
    public function attendance_trinar(Request $request)
    {
        if(\Auth::user()->hasRole('administrator')){
            $branchIds = Branchs::get()->pluck('id')->toArray();
        }
        else{
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $user = User::query();
        $filterName = $request->name;
        $filterMail = $request->email;
        $filterPhone = $request->phone;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        if(!empty($filterName)||!empty($filterMail) || !empty($filterPhone)){
            if(!empty($filterName))
                $user->orWhere('name', 'like', '%' .$filterName . '%');

            if(!empty($filterMail))
                $user->orWhere('email', 'like', '%' . $filterMail . '%');

            $ids = $user->pluck('id')->toArray();
            if((!is_null($fromDate)&&!is_null($toDate))&&(!empty($fromDate)&&!empty($toDate))){
                $players = TrainerAttendance::query()->whereIn('trainer_id',$ids)->whereBetween('created_at',[$fromDate,$toDate])->first();
            }
            else{
                $players =  TrainerAttendance::query()->whereIn('trainer_id',$ids);;
            }
        }else{
            if((!is_null($fromDate)&&!is_null($toDate))&&(!empty($fromDate)&&!empty($toDate))){

                $players = TrainerAttendance::query()->whereBetween('created_at',[$fromDate,$toDate]);
                //dd($players);
            }
            else{
                $players = TrainerAttendance::query();
            }
        }


        if ($request->filter){
            if($request->pdf){
                $FilePdf = new ConvertDataToPDF("Dashboard.reports.pdf.attendance_trinars",$players->get()->toArray()," تقرير حضور و انصراف المدربين.pdf");
            }
            if($request->excel){
                $ExportToExcelSheet  = new ExportToExcelSheet($players->get()->toArray(),'Dashboard.reports.pdf.attendance_trinars');
                return Excel::download($ExportToExcelSheet , ' تقرير الحضور.xlsx');
            }
        }

        $players = $players->paginate(10);
        return view('Dashboard.reports.trinar_attendance',compact('players','players','players'));
    }
}
