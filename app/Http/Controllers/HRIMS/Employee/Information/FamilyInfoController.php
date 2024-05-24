<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_FamilyBg;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class FamilyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $query = Users::with('family.fam_relation')
            ->find($id);

        $data = array(
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/familyInfo',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = array();

        $name_services = new NameServices;

        $id = $request->id;

        $query = _FamilyBg::with('fam_relation')
            ->where('user_id',$id)
            ->orderBy('dob','DESC')
            ->get()
            ->map(function($query) use ($name_services) {
                $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                $contact_no = null;
                if($query->contact_no){
                    $contact_no = '0'.$query->contact_no;
                }
                return [
                    'id' => $query->id,
                    'name' => $name,
                    'relation' => $query->fam_relation->name,
                    'dob' => date('m/d/Y', strtotime($query->dob)),
                    'contact' => $contact_no,
                    'email' => $query->email
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['relation'];
                $data_list['f4'] = $r['dob'];
                $data_list['f5'] = $r['contact'];
                $data_list['f6'] = $r['email'];
                $data_list['f7'] = '<button class="btn btn-info btn-info-scan edit-fam"
                                            data-id="'.$r['id'].'">
                                            <span class="fa fa-edit"></span></button>
                                    <button class="btn btn-danger btn-danger-scan delete-fam"
                                            data-id="'.$r['id'].'">
                                            <span class="fa fa-trash"></span></button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

     /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
