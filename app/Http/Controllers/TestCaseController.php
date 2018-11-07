<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TestCase;
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

    public function getData(Request $request) {
        $user = User::where('remember_token', $request->input('id'))->get();
        $testcases = TestCase::where('user_id',$user[0]->id)->get();
    }

    public function getLatestId(Request $request) {
        $user = User::where('remember_token', $request->input('id'))->get();
        $test_case_count = TestCase::where('user_id',$user[0]->id)->count() + 1;
        foreach(TestCase::where('user_id',$user[0]->id)->cursor() as $testcase) {
            print($testcase);
        }
        die();
        return ["testCaseId" => $test_case_count];
    }
}
