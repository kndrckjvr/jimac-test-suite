<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Module;
use App\TestScenario;

class ModuleController extends Controller
{
    //
    public function createModule(Request $request) {
        if(empty($request->input('moduleName')))
            return response()->json([
                'status' => 0,
                'error' => 'The Module Name is required.'
            ]);

        if(Module::where([
            ['module_name', '=', $request->input('moduleName')],
            ['test_case_id', '=', $request->input('testCaseId')]
            ])->count()) {
            return response()->json([
                'status' => 0,
                'error' => 'The Module Name has already been taken.'
            ]);
        }
        
        $module = new Module;
        $module->module_name = ucwords($request->input('moduleName'));
        $module->test_case_id = $request->input('testCaseId');
        $module->save();

        return response()->json([
            'status' => 1,
            'moduleId' => $module->id
        ]);
    }

    public function deleteModule(Request $request) {
        foreach($request->input('moduleId') as $id) {
            Module::where('id', $id)->delete();
        }
        return response()->json(["status"=>1]);
    }

    public function getData(Request $request) {
        $api_data = array();
        foreach(Module::where('test_case_id', $request->input('testCaseId'))->cursor() as $module) {
            $data['moduleName'] = $module->module_name;
            $data['scenarios'] = TestScenario::where('module_id', $module->id)->count();
            $data['passed'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getPassed($module->id)*100, 2, '.', '');
            $data['failed'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getFailed($module->id)*100, 2, '.', '');
            $data['skipped'] = number_format((float)app('App\Http\Controllers\TestScenarioController')->getSkipped($module->id)*100, 2, '.', '');
            $data['createdAt'] = date('F j, Y', strtotime($module->created_at->toDateString()));
            $data['updatedAt'] = date('F j, Y', strtotime($module->updated_at->toDateString()));
            $data['moduleId'] = $module->id;
            array_push($api_data, $data);
        }
        return response()->json([
            'status' => 1,
            'modules' => $api_data
        ]);
    }

    public function getLatestId(Request $request) {
        $statement = DB::select("show table status like 'modules'");
        return response()->json(['moduleId' => $statement[0]->Auto_increment]);
    }

    public function getPassed($testCaseId) {
        $result = 0;
        $total = 0;
        foreach(Module::where('test_case_id', $testCaseId)->cursor() as $module) {            
            foreach(
                TestScenario::where([
                    ['module_id', '=', $module->id], 
                    ['status', '=', '1']])->cursor() as $testScenario)
                $result = $result + 1;      
            $total = $total + TestScenario::where('module_id', $module->id)->count();
        }
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getFailed($testCaseId) {
        $result = 0;
        $total = 0;
        foreach(Module::where('test_case_id', $testCaseId)->cursor() as $module) {            
            foreach(
                TestScenario::where([
                    ['module_id', '=', $module->id], 
                    ['status', '=', '0']])->cursor() as $testScenario)
                $result = $result + 1;      
            $total = $total + TestScenario::where('module_id', $module->id)->count();
        }
        if($total <= 0) return 0;
        return ($result / $total);
    }

    public function getSkipped($testCaseId) {
        $result = 0;
        $total = 0;
        foreach(Module::where('test_case_id', $testCaseId)->cursor() as $module) {            
            foreach(
                TestScenario::where([
                    ['module_id', '=', $module->id], 
                    ['status', '=', '2']])->cursor() as $testScenario)
                $result = $result + 1;      
            $total = $total + TestScenario::where('module_id', $module->id)->count();
        }
        if($total <= 0) return 0;
        return ($result / $total);
    }
}
