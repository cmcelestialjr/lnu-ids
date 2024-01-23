<?php

namespace App\Http\Controllers;

use App\Exports\Export;
use App\Http\Controllers\Controller;
use App\Models\EducPrograms;
use App\Models\Ludong\LudongStudents;
use App\Models\Signatory;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SignatoryController extends Controller
{
    public function table(Request $request){
        $name_services = new NameServices;
        $data = array();

        $validator = Validator::make($request->all(), [
            'type' => 'required|string'
        ]);

        if($validator->fails()){
            return  response()->json($data);
        }
        $url_previous = url()->previous();
        $url_str_replace = str_replace('//','',$url_previous);
        $url_explode = explode('/',$url_str_replace);
        $url_system = $url_explode[2];
        $type = $request->type;
        
        $signatory = Signatory::where('type',$type)
            ->where('system_shorten',$url_system)
            ->get()
            ->map(function($query) use ($name_services) {
                $signatory = '';
                if($query->signatory_id!=NULL){
                    $signatory = $name_services->lastname($query->signatory->lastname,$query->signatory->firstname,$query->signatory->middlename,$query->signatory->extname);
                }
                $updated_by = $name_services->lastname($query->updated_by_id->lastname,$query->updated_by_id->firstname,$query->updated_by_id->middlename,$query->updated_by_id->extname);
                $dateTime = date('F d, Y h:ia',strtotime($query->updated_at));
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'signatory' => $signatory,
                    'updated_by' => $updated_by,
                    'dateTime' => $dateTime
                ];
            })->toArray();

        if(count($signatory)>0){
            $x = 1;
            foreach($signatory as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = '<button class="btn btn-info btn-info-scan btn-xs signatoryModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-pen"></span> &nbsp; 
                                        <span id="signatoryType'.$r['id'].'">'.$r['signatory'].'</span>
                                    </button>';
                $data_list['f4'] = $r['updated_by'];
                $data_list['f5'] = $r['dateTime'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    

    public function modal(Request $request){
        $data = array('id' => $request->id);
        return view('signatory/modal',$data);
    }
    public function update(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $signatory_name = '';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3 || $user_access_level==4){
            $name_services = new NameServices;
            $rules = [
                'signatory' => 'nullable|numeric',
                'id' => 'required|numeric'
            ];
        
            $customMessages = [
                'signatory.numeric' => 'Signatory must be number.',
                'id.required' => 'ID is required.'
            ];
        
            $validator = Validator::make($request->all(), $rules, $customMessages);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400); // Return validation errors
            }

            $user = Auth::user();
            $updated_by = $user->id;

            $signatory = $request->signatory;
            $id = $request->id;
            
            if($signatory){
                $signatory_user = Users::find($signatory);
                $signatory_check = Signatory::find($id);
                if($signatory_user && $signatory_check){
                    
                    $signatory_name = $name_services->lastname($signatory_user->lastname,$signatory_user->firstname,$signatory_user->middlename,$signatory_user->extname);
                    
                }
            }else{
                $signatory = NULL;
            }
            
            Signatory::where('id',$id)
                ->update(['signatory_id' => $signatory,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
            $result = 'success';
            
        }
        $response = array('result' => $result,
                         'signatory_name' => $signatory_name);
        return response()->json($response);
    }    
}