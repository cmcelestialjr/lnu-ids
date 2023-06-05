<?php

namespace App\Http\Controllers\HRIMS\Deduction;
use App\Http\Controllers\Controller;
use App\Models\HRDeduction;
use App\Models\HRDeductionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
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
    public function viewModal(Request $request){
        return $this->_viewModal($request);
    }
    public function viewModalTable(Request $request){
        return $this->_viewModalTable($request);
    }
    private function _table($request){
        $data = array();
        $query = HRDeductionGroup::orderBy('name')->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span> 
                                    </button>';
                $data_list['f4'] = '<button class="btn btn-success btn-success-scan btn-sm view"
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
        return view('hrims/deduction/group/newModal');
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = mb_strtoupper($request->name);
            $check = HRDeductionGroup::where('name',$name)->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new HRDeductionGroup(); 
                $insert->name = $name;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _viewModal($request){
        $id = $request->id;
        $query = HRDeductionGroup::where('id',$id)->first();
        $data = array(
            'query' => $query
        );
        return view('hrims/deduction/group/viewModal',$data);
    }
    private function _viewModalTable($request){
        $data = array();
        $query = HRDeduction::where('group_id',$request->id)->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _updateModal($request){
        $id = $request->id;
        $query = HRDeductionGroup::where('id',$id)->first();
        $data = array(
            'query' => $query
        );
        return view('hrims/deduction/group/updateModal',$data);
    }
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = mb_strtoupper($request->name);
            $check = HRDeductionGroup::where('id','!=',$id)
                ->where(function ($query) use ($name) {
                    $query->where('name',$name);
                })->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                HRDeductionGroup::where('id', $id)
                ->update([
                    'name' => $name,
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