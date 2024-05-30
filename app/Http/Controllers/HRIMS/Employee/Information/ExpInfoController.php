<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;

        $query = _Work::where('user_id',$id)
            ->get();

        $data = array(
            'query' => $query
        );
        return view('hrims/employee/information/expInfo',$data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = array();

        $id = $request->id;

        $query = _Work::with('emp_stat')
            ->where('user_id',$id)
            ->orderBy('date_from','DESC')
            ->get()
            ->map(function($query) {
                $date_to = 'present';
                $sg = 'N/A';

                if($query->date_to!='present'){
                    $date_to = date('m/d/Y',strtotime($query->date_to));
                }
                if($query->sg>=1){
                    $sg = $query->sg;
                }

                return [
                    'id' => $query->id,
                    'date_from' => date('m/d/Y',strtotime($query->date_from)),
                    'date_to' => $date_to,
                    'position_title' => $query->position_title,
                    'office' => $query->office,
                    'sg' => $sg,
                    'emp_stat' => $query->emp_stat->name,
                    'gov_service' => $query->gov_service,
                    'docs' => $query->docs
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['date_from'];
                $data_list['f3'] = $r['date_to'];
                $data_list['f4'] = $r['position_title'];
                $data_list['f5'] = $r['office'];
                $data_list['f6'] = $r['sg'];
                $data_list['f7'] = $r['emp_stat'];
                $data_list['f8'] = $r['gov_service'];
                if($r['docs']){
                    $button_options = '<button class="btn btn-primary btn-primary-scan doc-exp"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }else{
                    $button_options = '<button class="btn btn-warning btn-warning-scan doc-exp"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }
                $data_list['f9'] = $button_options;
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    public function showDoc(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $fid = $request->fid;

        $check = _Work::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $doc = 'assets/pdf/pdf_error.pdf';
        if($check->doc){
            $doc = $check->docs;
        }

        $data = array(
            'doc' => $doc
        );
        return view('hrims/employee/information/expInfoDoc',$data);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showMoreValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'fid' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'fid.required' => 'FID is required',
            'fid.numeric' => 'FID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

}
