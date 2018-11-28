<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestScenario;

class TestScenarioController extends Controller
{
    //
    public function getData(Request $request) {
        $api_data = array();
        foreach(TestScenario::where('module_id', $request->input('moduleId'))->cursor() as $testScenario) {
            $data['scenarioId'] = $testScenario->test_scenario_id;
            $data['testScenarioName'] = $testScenario->test_scenario_name;
            $data['status'] = ($testScenario->status == 1) ?
                'Passed' : ($testScenario->status == 0 ? 'Failed' : 'Skipped');
            $data['updated'] = $testScenario->updated_at->format('F j, Y (h:i A)');#strtotime();#date('F j, Y | h:i:s A',));
            $data['testScenarioId'] = $testScenario->id;
            array_push($api_data, $data);
        }

        return response()->json([
                'status' => 1,
                'testScenario' => $api_data
            ]);
        print_r($api_data);
    }

    public function getLatestTitle() {
        
    }

    public function getPassed($moduleId) {
        $result = 0;
        foreach(
        TestScenario::where([
            ['module_id', '=', $moduleId],
            ['status', '=', 1]
            ])->cursor() as $scenario) {
                $result = $result + 1;
        }
        if($result <= 0) {
            return 0;
        }
        $total = TestScenario::where('module_id', $moduleId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getFailed($moduleId) {
        $result = 0;
        foreach(
        TestScenario::where([
            ['module_id', '=', $moduleId],
            ['status', '=', 0]
            ])->cursor() as $scenario) {
                $result = $result + 1;
        }
        if($result <= 0) {
            return 0;
        }
        $total = TestScenario::where('module_id', $moduleId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getSkipped($moduleId) {
        $result = 0;
        foreach(
        TestScenario::where([
            ['module_id', '=', $moduleId],
            ['status', '=', 2]
            ])->cursor() as $scenario) {
                $result = $result + 1;
        }
        if($result <= 0) {
            return 0;
        }
        $total = TestScenario::where('module_id', $moduleId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }
}
