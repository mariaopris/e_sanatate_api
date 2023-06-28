<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class StatisticController extends Controller
{
    public function index()
    {
        $date = Carbon::now();
        $date = $date->toDateString();
        $current_month = substr($date, 5);
        $current_month = substr_replace($current_month,'', -3);

        $statistics = Statistic::all();
        $no_total_diagnostics = 0;
        $no_total_users = 0;
        $no_monthly_diagnostics = 0;
        $no_monthly_users = 0;

        foreach ($statistics as $stat){
            $no_total_diagnostics = $no_total_diagnostics + $stat->no_new_diagnostic_h + $stat->no_new_diagnostic_l;
            $no_total_users = $no_total_users + $stat->no_new_user;
            $month = substr($stat->date, 5);
            $month = substr_replace($month,'', -3);
            if($month === $current_month) {
                $no_monthly_diagnostics = $no_monthly_diagnostics + $stat->no_new_diagnostic_h + $stat->no_new_diagnostic_l;
                $no_monthly_users = $no_monthly_users + $stat->no_new_user;
            }
        }
        return response()->json(['statistics' => $statistics,'no_total_diagnostics' => $no_total_diagnostics,'no_monthly_users' => $no_monthly_users,
                                 'no_total_users' => $no_total_users, 'no_monthly_diagnostics' => $no_monthly_diagnostics]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function addNewUser() {
        $date = Carbon::now();
        $date = $date->toDateString();
        $current_statistic = Statistic::where('date','=', $date)->first();

        if($current_statistic != null) {
            $current_statistic->no_new_user++;
            $current_statistic->update();
        } else {
            try {
                $statistic = Statistic::create([
                    'date' => $date,
                    'no_new_user' => 1,
                    'no_new_diagnostic_h' => 0,
                    'no_new_diagnostic_l' => 0,
                ]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Statistic saved!']);
    }

    public function addNewDiagnosticHeart() {
        $date = Carbon::now();
        $date = $date->toDateString();
        $current_statistic = Statistic::where('date','=', $date)->first();

        if($current_statistic != null) {
            $current_statistic->no_new_diagnostic_h++;
            $current_statistic->update();
        } else {
            try {
                $statistic= Statistic::create([
                    'date' => $date,
                    'no_new_user' => 0,
                    'no_new_diagnostic_h' => 1,
                    'no_new_diagnostic_l' => 0,
                ]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Statistic saved!']);
    }

    public function addNewDiagnosticLungs() {
        $date = Carbon::now();
        $date = $date->toDateString();
        $current_statistic = Statistic::where('date','=', $date)->first();

        if($current_statistic != null) {
            $current_statistic->no_new_diagnostic_l++;
            $current_statistic->update();
        } else {
            try {
                $statistic= Statistic::create([
                    'date' => $date,
                    'no_new_user' => 0,
                    'no_new_diagnostic_h' => 0,
                    'no_new_diagnostic_l' => 1,
                ]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Statistic saved!']);
    }

    public function getLastDiagnostic(Request $request) {
        $user_id = $request->user_id;
        $diagnostic_heart = Diagnostic::select('id', 'result_short')->where('user_id', $user_id)->where('type', 1)->latest('id')->first();
        $diagnostic_lungs = Diagnostic::select('id', 'result_short')->where('user_id', $user_id)->where('type', 2)->latest('id')->first();

        return response()->json(['diagnostic_heart' => $diagnostic_heart, 'diagnostic_lungs' => $diagnostic_lungs]);
    }
}
