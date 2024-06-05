<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Levels;
use App\Models\Packages;
use App\Models\Player_Sport;
use App\Models\PlayerPriceList;
use App\Models\Players;
use App\Http\Requests\StorePlayersRequest;
use App\Http\Requests\UpdatePlayersRequest;
use App\Models\PlayersFiles;
use App\Models\Receipts;
use App\Models\Sports;
use App\Models\SportsAndLevelTrainer;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;


class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Players::with('sports')->with('branches')
            ->orderBy('id','desc');

        if(!\Auth::user()->hasRole('administrator')){
                $branchIds = \Auth::user()->branches->pluck('id')->toArray();
                $players->whereIn('branch_id', $branchIds);
            }

        $players = $players->get();
        return view('Dashboard.Players.index',compact('players'));
    }

    public function getPlayers(Request $request)
    {
        $check = SportsAndLevelTrainer::where('user_id', $request->user_id)->exists();
        if(\Auth::user()->hasRole('administrator')){
            $branchIds = Branchs::get()->pluck('id')->toArray();
        }
        else{
            $branchIds = \Auth::user()->branches->pluck('id')->toArray();
        }
        $players = null;
        if ($check)
        {
            $players = Players::whereHas('playerPriceLists',function ($q) use ($request){
                            $q->where('sport_id',$request->sport_id)
                                ->where('level_id',$request->level_id);
                        })->whereIn('branch_id',$branchIds)
                ->with('branches')->get();

        }

        return response()->json($players??[]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches =  \Auth::user()->branches;
        $packages = Packages::get();

        return view("Dashboard.Players.create",compact('branches','packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePlayersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlayersRequest $request)
    {
        $collective = Packages::query()->find($request->package_id)->collective;
        //dd($request->dd());
      $player =  Players::create([
            'name'=>$request->name,
            'birth_day'=>$request->birth_day,
            'join_day'=>$request->join_date,
            'address'=>$request->address,
            'study'=>$request->study,
            'father_name'=>$request->father_name,
            'father_phone'=>$request->father_phone,
            'anther_phone'=>$request->anther_phone,
            'father_job'=>$request->father_job,
            'father_email'=>$request->father_email,
            'branch_id'=>$request->branches_id,
//            'sport_id'=>$request->sport_id,
//            'level_id'=>$request->level_id,
            'package_id'=>$request->package_id,
            'collective'=>$collective,
            'anther_sport'=>$request->anther_sports,
            'join_by'=>$request->join_by,
            'goal_of_sport'=>$request->goal_of_sport,
            'note'=>$request->note,
            "personal_image" => $request->personal_image,
            "father_national_image" => $request->father_national_image,
            "birth_certificate" => $request->birth_certificate,
            "medical" => $request->medical,
            "deleteable" => 1,
//        تقرير الخزينه اليومي جعل كل فرع يشوف خزنته فقط والادمن يشوف كل الخزن
        ]);

        if (count($request->sport_id) > 0){
            $count = count($request->sport_id);
            for ($i = 0; $count > $i;$i++)
            {
                Player_Sport::create([
                    'player'=>$player->id,
                    'sport'=>$request->sport_id[$i],
                    'branch_id'=>$request->branch_id[$i],
                    'level_id'=>$request->level_id[$i],
                    'price_list'=>$request->price_list[$i],
                ]);
            }
        }

        if($request->file){

            for($x=0;  $x < count($request->name_of_file)   ;$x++)
            {
                $media_name=$request->name_of_file[$x];
                $objfile =$request->file[$x];
                $fileName = time() . $objfile->getClientOriginalName();
                $pathFile = public_path("storage/studentsMedia");
                $objfile->move($pathFile, $fileName);
                $fileNamePath = "storage/PlayersMedia" . '/' . $fileName;
                PlayersFiles::create([
                    'player_id'=> $player->id,
                    'file_name'=>$media_name,
                    'file_path'=> $fileNamePath,
                ]);

            }

        }

        if(count($request->price_list) > 0){
            foreach ($request->price_list as $price_list)
            {
                if($price_list)
                    PlayerPriceList::create([
                        'player_id'=>$player->id,
                        'price_list_id'=>$price_list
                    ]);
            }
        }
        return redirect()->route('player.index')->with('message','تم اضافه اللاعب بنجاح ');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Players  $players
     * @return \Illuminate\Http\Response
     */
    public function show(Players $players)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Players  $players
     * @return \Illuminate\Http\Response
     */
    public function edit(Players $player)
    {
        if(\Auth::user()->hasRole('administrator'))
            $branches = Branchs::get();
        else
            $branches =  \Auth::user()->branches;
        $packages = Packages::get();
        $playerSports = Player_Sport::where('player',$player->id)->get();
        //dd($playerSport);
//        dd($player_files);
        return view("Dashboard.Players.edit",compact('branches','player','playerSports','packages'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlayersRequest  $request
     * @param  \App\Models\Players  $players
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlayersRequest $request, Players $player)
    {
        //dd($request->all());

        $player->name = $request->name;
        $player->birth_day = $request->birth_day;
        $player->join_day = $request->join_date;
        $player->address = $request->address;
        $player->study = $request->study;
        $player->father_name = $request->father_name;
        $player->father_phone = $request->father_phone;
        $player->anther_phone = $request->anther_phone;
        $player->father_job = $request->father_job;
        $player->father_email = $request->father_email;
        $player->branch_id = is_array($request->branch_id)&&count($request->branch_id) > 1? null:$request->branch_id[0];
        //$player->sport_id = $request->sport_id;
        //$player->level_id = $request->level_id;
        $player->package_id = $request->package_id;
        $player->anther_sport = $request->anther_sports;
        $player->join_by = $request->join_by;
        $player->goal_of_sport = $request->goal_of_sport;
        $player->note = $request->note;
        $player->personal_image =  $request->personal_image;
        $player->father_national_image =  $request->father_national_image;
        $player->birth_certificate =  $request->birth_certificate;
        $player->medical =  $request->medical;
        $player->save();
        if($request->file){
            PlayersFiles::where('player_id',$player->id)->delete();

            for($x=0;  $x < count($request->name_of_file)   ;$x++)
            {
                $media_name=$request->name_of_file[$x];
                $objfile =$request->file[$x];
                $fileName = time() . $objfile->getClientOriginalName();
                $pathFile = public_path("storage/studentsMedia");
                $objfile->move($pathFile, $fileName);
                $fileNamePath = "storage/PlayersMedia" . '/' . $fileName;
                PlayersFiles::create([
                    'player_id'=> $player->id,
                    'file_name'=>$media_name,
                    'file_path'=> $fileNamePath,
                ]);

            }

        }
        if(is_array($request->branch_id)&&count($request->branch_id) > 1){
            Player_Sport::where('player_id',$player->id)->delete();
            for($x=0;  $x < count($request->branch_id);$x++)
            {
                Player_Sport::create([
                    'player'=> $player->id,
                    'branch_id'=>$request->branch_id[$x],
                    'level_id'=> $request->level_id[$x],
                    'sport'=>$request->sport_id[$x],
                    'price_list'=> $request->price_list[$x],
                ]);

            }
        }
        if($request->price_list){
            PlayerPriceList::where('player_id',$player->id)->delete();
            $priceListCount = count($request->input('price_list'));
            for($counter = 0 ; $counter < $priceListCount;  $counter++ ){
                if($request->price_list[$counter])
                    PlayerPriceList::create([
                        'player_id'=>$player->id,
                        'price_list_id'=>$request->price_list[$counter]
                    ]);

            }
        }
        return redirect()->route('player.index')->with('message','تم تعديل اللاعب بنجاح ');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Players  $players
     * @return \Illuminate\Http\Response
     */
    public function destroy(Players $player)
    {
        if($player->deleteable == 1){
            PlayersFiles::where('player_id',$player->id)->delete();
            PlayerPriceList::where('player_id',$player->id)->delete();


            $player->delete();
            return redirect()->route('player.index')->with('error','تم حذف اللاعب مع ملفاته بنجاح ');
        }else{
            return redirect()->route('player.index')->with('error','لا يمكن حذف اللاعب ');
        }
    }


    public function deleteFiles($id){
        $player_file = PlayersFiles::find($id);
        File::delete($player_file->file_path);
        $player_file->delete();

        return back()->with('error','تم حذف الفايل اللاعب بنجاح ');
    }

    public function getSports(Request  $request){
        $branches_request = $request->branch_id;
        $sports = Sports::whereHas('branches' , function ($query) use ($branches_request){
            $query->whereIn('branch_id',$branches_request);
        })->get();
        if($request->player_id){
            $player = Players::find($request->player_id);
        }

        $selected='';
        $option='';
        foreach ($sports as  $sport){
            if($player->sport_id == $sport->id ){
                        $selected = 'selected';
            }
            $option .= "
      <option value=$sport->id $selected > $sport->name </option> ";
            $selected = ' ';
        }

        return     \Response::json(['data'=>$option])  ;

    }

    /**
    * get players data for receipts
    */
    public function getPlayerData(Request $request)
    {

        $player = Players::with('playerPriceLists','package')->find($request->player_id);
        $selected = "";
        $id=0;
        if($request->receipt_id){
         $receipt =    Receipts::find($request->receipt_id);
         $id = $receipt->price_list_id ?  $receipt->price_list_id : $receipt->package_id;

        }
        $priceLists = $player->playerPriceLists;

        $optionPriceList='  <option value="" selected>اختر  قائمه سعر  </option>  ';
        foreach ($priceLists as $pl){
            if($id == $pl->id){
                $selected = "selected";
            }
            $optionPriceList .=  "<option $selected data-typeprice='price_list'   value=$pl->id  > $pl->name </option> ";
            $selected = ' ';

        }
        /***************** Package Options  *******************************/

        $packages = $player->package;
        if ($packages)
        {

            if($id == $packages?->id){
                $selected = "selected";
            }
            $optionPriceList .= " <option $selected  data-typeprice='package' value=$packages?->id  > $packages?->name --package </option> ";
        }
        $selected = "";
        return     \Response::json(['optionPriceList'=>$optionPriceList])  ;

    }

    public function migratePlayersData(){
        $players = Players::whereNotNull('branch_id')->get();
        //dd($players);
        foreach ($players as $player){
            if(count($player->playerPriceLists) > 0){
                foreach ($player->playerPriceLists as $priceList){
                    Player_Sport::create([
                        'player'=> $player->id,
                        'branch_id'=>$player->branch_id,
                        'level_id'=> $priceList->level_id,
                        'sport'=>$priceList->sport_id,
                        'price_list'=>$priceList->id,
                    ]);
                }
            }
        }
    }

    public function getPlayer(Request $request)
    {
        $branch_id = $request->branch_id;

        $players = Players::where('branch_id',$branch_id)->get();
        $selected = "";
        $id=0;

        $optionPlayer='  <option value="" selected>اختر لاعب   </option>  ';
        foreach ($players as $pl){
            $optionPlayer .=  "<option $selected data-typeprice='price_list'   value=$pl->id  > $pl->name </option> ";
            $selected = ' ';
        }
        return     \Response::json(['playersDatalist'=>$optionPlayer])  ;

    }

}
