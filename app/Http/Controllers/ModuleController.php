<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;

class ModuleController extends Controller
{
    //
    public function getData(Request $request) {
        $modules = Module::where('test_case_id', $request->input('testCaseId'));
    }

    public function getLatestId(Request $request) {
        $module_count = Module::where('test_case_id', $request->input('testCaseId'))->count() + 1;
        return response()->json(["moduleId"=>$module_count]);
    }

    public function getPassed($testCaseId) {
        $result = 0;

        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getPassed($module->id);
        }
        if($result <= 0) {
            return 0;
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total);
    }

    public function getFailed($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getFailed($module->id);
        }
        if($result <= 0) {
            return 0;
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total);
    }

    public function getSkipped($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getSkipped($module->id);
        }
        if($result <= 0) {
            return 0;
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total);
    }
}
