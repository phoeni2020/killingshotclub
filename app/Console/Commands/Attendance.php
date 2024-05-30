<?php

namespace App\Console\Commands;

use App\Models\Branchs;
use App\Models\EmployeeBranch;
use App\Models\EmpolyeeAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Attendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $branchIds = Branchs::get()->pluck('id')->toArray();
        $employees = EmployeeBranch::query()->whereIn('branch_id',$branchIds)->pluck('employee_id')->toArray();
        $employees = User::query()->whereIn('id',$employees)->get();
        foreach ($employees as $employee){
            $yesterday = Carbon::yesterday();
            $checkAttend =   EmpolyeeAttendance::where('user_id',$employee->id)->whereDate('check_in',$yesterday->format('Y-m-d'))->first();
            if($checkAttend){
                $logTime = Carbon::make($checkAttend->check_in)->addHours(8);
                if($yesterday->format('Y-m-d')!= $logTime->format('Y-m-d')){
                    $logTime =  Carbon::make($checkAttend->check_in)->endOfDay()->format('Y-m-d H:i:s');
                }
                $attendance_id =   $checkAttend->id;
                $attendance =   EmpolyeeAttendance::find($attendance_id);
                $attendance->check_out = $logTime;
                $attendance->save();
            }
        }
    }
}
