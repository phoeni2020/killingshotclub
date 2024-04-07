<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Players;
use App\Models\PriceList;
use App\Models\Receipts;
use App\Models\ReceiptsPay;
use App\Models\ReceiptTypes;
use App\Models\Sports;
use App\Models\Stadium;
use App\Models\StadiumsRentTable;
use App\Models\TrainerAndPlayer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::connection()->enableQueryLog();
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
        $sport_id = $request->input('sport_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');
        // Add more filter parameters as needed

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
        $queries = DB::getQueryLog();

//        dd($queries);
        $total = $subscriptionsSum + $otherIncome + $rentAndMaintance + $otherExpense;

        // Return the report view with the filtered data
        return view('Dashboard.reports.income_reports',
            compact( 'branches',
                'trainers','players','sports','total','otherIncome','subscriptionsSum','rentAndMaintance','otherExpense'));
    }


    public function recipt_report(Request $request)
    {
        DB::connection()->enableQueryLog();
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
        $sport_id = $request->input('sport_id');
        $startDate = $request->input('fromDate');
        $endDate = $request->input('toDate');
        $search_keyword = $request->input('search_keyword');
        $player = $request->input('player');
        $trainer = $request->input('trainer');
        $type = $request->input('type');
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
        if($type)
            $Receipts = $receipts->whereHas('receiptType' , function($query) use ($type){
                $query->where('type',$type);
            });
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

        $receipts = $receipts->orderBy('id','desc')->paginate(10);
        $queries = DB::getQueryLog();

//        dd($queries);
        $receiptTypes= ReceiptTypes::query()->get();
        // Return the report view with the filtered data
        return view('Dashboard.reports.safe_reports',
            compact( 'branches',
                'trainers','players','receiptTypes','sports','receipts'));
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
        $search_keyword = $request->input*('search_keyword');
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
        if ($search_keyword) {
            $stadiums_tent_table->where('name', 'like', "%" . $search_keyword . "%")
                ->orWhereHas('traniers', function ($q) use ($search_keyword) {
                    $q->where('name', 'like', "%" . $search_keyword . "%");
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
        return view('Dashboard.reports.stadiums_reports',
            compact('reports', 'branches', 'stadiums'));
    }
}
