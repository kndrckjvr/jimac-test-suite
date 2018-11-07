<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;

class ModuleController extends Controller
{
    //
    public function getPassed($testCaseId) {
        $result = 0;

        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getPassed($module["id"]);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total) * 100;
    }

    public function getFailed($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getFailed($module["id"]);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total) * 100;
    }

    public function getSkipped($testCaseId) {
        $result = 0;
        foreach(
        Module::where('test_case_id', $testCaseId)->cursor() as $module) {
            $result = app('App\Http\Controllers\TestScenarioController')->getSkipped($module["id"]);
        }
        $total = Module::where('test_case_id', $testCaseId)->count();
        return ($result / $total) * 100;
    }
}
