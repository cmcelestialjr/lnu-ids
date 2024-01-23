<?php

namespace App\Http\Controllers\FMS\Accounting\Fees;
use App\Http\Controllers\Controller;
use App\Models\EducFees;
use App\Models\EducFeesType;
use App\Models\FundServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    public function newModal(Request $request){
        return $this->_newModal($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function updateModal(Request $request){
        return $this->_updateModal($request);
    }
    public function updateSubmit(Request $request){
        return $this->_updateSubmit($request);
    }
    private function _table($request){
        $data = array();
        $query = EducFees::get()
            ->map(function($query) {
                
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'type' => $query->type->name,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['type'];
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span>
                                    </button>
                                    <button class="btn btn-info btn-info-scan btn-sm view"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _newModal($request){
        $type = EducFeesType::get();
        $data = array(
            'type' => $type
        );
        return view('fms/accounting/fees/list/newModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
            $type = $request->type;
            $check = EducFees::where('name',$name)
                ->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new EducFees(); 
                $insert->name = $name;
                $insert->type_id = $type;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _updateModal($request){
        $id = $request->id;
        $query = EducFees::where('id',$id)->first();
        $type = EducFeesType::get();
        $data = array(
            'query' => $query,
            'type' => $type
        );
        return view('fms/accounting/fees/list/updateModal',$data);
    }
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $type = $request->type;
            $check = EducFees::where(function ($query) use ($name) {
                    $query->where('name',$name);
                })
                ->where('id','!=',$id)->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                EducFees::where('id', $id)
                ->update(['name' => $name,
                    'type_id' => $type,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}