<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Module;
use App\TestScenario;

class ModuleController extends Controller
{
    //
    public function getData(Request $request) {
        $api_data = array();
        foreach(Module::where('test_case_id', $request->input('testCaseId'))->cursor() as $module) {
            $data['moduleName'] = $module->module_name;
            $data['scenarios'] = TestScenario::where('module_id', $module->id)->count();
            $data['passed'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getPassed($module->id)*100, 2, '.', '');
            $data['failed'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getFailed($module->id)*100, 2, '.', '');
            $data['skipped'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getSkipped($module->id)*100, 2, '.', '');
            $data['moduleId'] = $module->id;
            array_push($api_data, $data);
        }
        return response()->json(["status"=>1,"modules" => $api_data]);
    }

    public function getLatestId(Request $request) {
        $module_count = Module::where('test_case_id', $request->input('testCaseId'))->count() + 1;
        return response()->json(["moduleId"=>$module_count]);
    }

    public function getPassed($testCaseId) { 
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = $result + app('App\Http\Controllers\TestScenarioController')->getPassed($module->id);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getFailed($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = $result + app('App\Http\Controllers\TestScenarioController')->getFailed($module->id);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getSkipped($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = $result + app('App\Http\Controllers\TestScenarioController')->getSkipped($module->id);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        if($total <= 0) return 0;
        return ($result / $total);
    }
}
