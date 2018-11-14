<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\TestCase;
use App\Module;
use App\TestScenario;
use App\User;

class TestCaseController extends Controller
{
    //
    public function getUserTestCases(Request $request) {

    }

    public function uploadTestCase() {

    }

    public function downloadTemplate() {
        
    }

    public function editTestCase(Request $request) {
        if(TestCase::where('test_case_name', $request->input('testCaseTitle'))->count())
            return response()->json([
                'status' => 0,
                'error' => 'The Test Case Title has already been taken.']);
        $testCase = TestCase::find($request->input('testCaseId'));
        $testCase->test_case_name = $request->input('testCaseTitle');
        $testCase->save();
        return response()->json(['status' => 1]);
    }

    public function createTestCase(Request $request) {
        $user = User::where('remember_token', $request->input('token'))->get();
        if(TestCase::where([
            ['test_case_name', '=', $request->input('testCaseTitle')],
            ['user_id', '=', $request->input('token')]
            ])->count()) {
            return response()->json([
                            'status' => 0,
                            'error'=> 'The Test Case Title has already been taken.']);
        }
        
        $testcase = new TestCase;
        $testcase->test_case_name = ucwords($request->input('testCaseTitle'));
        $testcase->user_id = $user[0]->id;
        $testcase->save();

        return response()->json([
                            'status' => 1,
                            'testCaseId' => $testcase->id]);
    }

    public function deleteTestCase(Request $request) {
        $moduleIds = array();
        $testScenarioIds = array();
        foreach($request->input('testCaseId') as $test_case_id) {
            foreach(Module::where('test_case_id', $test_case_id)->cursor() as $module) {
                foreach(TestScenario::where('module_id', $module->id)->cursor() as $testScenario) {
                    array_push($testScenarioIds, $testScenario->id);
                }
                array_push($moduleIds, $module->id);
            }
            Module::whereIn('id', $moduleIds)->delete();
            TestScenario::whereIn('id', $testScenarioIds)->delete();
            TestCase::where('id', $test_case_id)->delete();
        }
        return response()->json(['status'=>1]);
    }

    public function getData(Request $request) {
        $user = User::where('remember_token', $request->input('token'))->get();
        $api_data = array();
        foreach(TestCase::where('user_id',$user[0]->id)->cursor() as $testcase) {
            $data['value'] = false;
            $data['testCaseName'] = $testcase->test_case_name;
            $data['modules'] = Module::where('test_case_id', $testcase->id)->count();
            $data['testScenarios'] = 0;
            foreach(Module::where('test_case_id', $testcase->id)->cursor() as $module) {
                foreach(TestScenario::where('module_id', $module->id)->cursor() as $testScenario) {
                    $data['testScenarios'] = $data['testScenarios'] + 1;
                }
            }
            $data['passed'] = number_format((float)app('App\Http\Controllers\ModuleController')->getPassed($testcase->id)*100, 2, '.', '');
            $data['failed'] = number_format((float)app('App\Http\Controllers\ModuleController')->getFailed($testcase->id)*100, 2, '.', '');
            $data['skipped'] = number_format((float)app('App\Http\Controllers\ModuleController')->getSkipped($testcase->id)*100, 2, '.', '');
            $data['createdAt'] = date('F j, Y', strtotime($testcase->created_at->toDateString()));
            $data['updatedAt'] = date('F j, Y', strtotime($testcase->updated_at->toDateString()));
            $data['testCaseId'] = $testcase->id;
            array_push($api_data, $data);
        }
            
        $statement = DB::select("show table status like 'test_cases'");
        return response()->json([
                            'status' => 1,
                            'testCases' => $api_data,
                            'testCaseId' => $statement[0]->Auto_increment]);
    }

    public function getLatestId(Request $request) {
        $statement = DB::select("show table status like 'test_cases'");
        return response()->json(['testCaseId' => $statement[0]->Auto_increment]);
    }
}
