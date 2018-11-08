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

    public function createTestCase(Request $request) {
        $user = User::where('remember_token', $request->input('id'))->get();  
        if(TestCase::where([
            ['test_case_name', '=', $request->input('testCaseTitle')],
            ['user_id', '=', $user[0]->id]
            ])->count()) {
            return response()->json([
                            "status" => 0,
                            "error"=> "The Test Case Title has already been taken."]);
        }
                               
        $testcase = new TestCase;
        $testcase->test_case_name = ucwords($request->input('testCaseTitle'));
        $testcase->user_id = $user[0]->id;
        $testcase->save();

        return response()->json([
                            "status" => 1,
                            "testCaseId" => $testcase->id]);
    }

    public function getData(Request $request) {
        $user = User::where('remember_token', $request->input('id'))->get();
        $api_data = array();
        foreach(TestCase::where('user_id',$user[0]->id)->cursor() as $testcase) {
            $data["value"] = false;
            $data["testCaseName"] = $testcase->test_case_name;
            $data["modules"] = Module::where('test_case_id', $testcase->id)->count();
            $data["passed"] = app('App\Http\Controllers\ModuleController')->getPassed($testcase->id);
            $data["failed"] = app('App\Http\Controllers\ModuleController')->getFailed($testcase->id);
            $data["skipped"] = app('App\Http\Controllers\ModuleController')->getSkipped($testcase->id);
            $data["createdBy"] = $testcase->created_at;
            $data["testCaseId"] = $testcase->id;
            array_push($api_data, $data);
        }
        
        $test_case_count = TestCase::where('user_id',$user[0]->id)->count() + 1;

        return response()->json([
                            "status" => 1,
                            "testCases" => $api_data,
                            "testCaseId" => $test_case_count]);
    }

    public function getLatestId(Request $request) {
        $user = User::where('remember_token', $request->input('id'))->get();
        $test_case_count = TestCase::where('user_id',$user[0]->id)->count() + 1;

        return response()->json(["testCaseId" => $test_case_count]);
    }
}
