<?php

namespace App\Http\Controllers\FMS\Accounting\Fund;
use App\Http\Controllers\Controller;
use App\Models\FundCluster;
use App\Models\FundFinancing;
use App\Models\FundSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClusterController extends Controller
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
    public function viewTable(Request $request){
        return $this->_viewTable($request);
    }
    private function _table($request){
        $data = array();
        $query = FundCluster::orderBy('code')->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'shorten' => $query->shorten,
                    'code' => $query->code
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span> 
                                    </button>
                                    <button class="btn btn-success btn-success-scan btn-sm view"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> 
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _viewTable($request){
        $data = array();
        $query = FundSource::where('fund_cluster_id',$request->id)->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'shorten' => $query->shorten,
                    'code' => $query->code,
                    'uacs' => $query->uacs
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['uacs'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _newModal($request){
        $fund_financing = FundFinancing::get();
        $data = array(
            'fund_financing' => $fund_financing
        );
        return view('fms/accounting/fund/cluster/newModal',$data);
    }
    private function _viewModal($request){
        $query = FundCluster::where('id',$request->id)->first();
        $data = array(
            'query' => $query
        );
        return view('fms/accounting/fund/cluster/viewModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
            $shorten = $request->shorten;
            $code = $request->code;
            //$fund_financing = $request->fund_financing;
            $check = FundCluster::where('name',$name)
                ->orWhere('shorten',$shorten)
                ->orWhere('code',$code)->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new FundCluster(); 
                //$insert->financing_id = $fund_financing;
                $insert->name = $name;
                $insert->shorten = $shorten;
                $insert->code = $code;
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
        $query = FundCluster::where('id',$id)->first();
        $fund_financing = FundFinancing::get();        
        $data = array(
            'query' => $query,
            'fund_financing' => $fund_financing
        );
        return view('fms/accounting/fund/cluster/updateModal',$data);
    }
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $shorten = $request->shorten;
            $code = $request->code;
            //$fund_financing = $request->fund_financing;
            $check = FundCluster::where('id','!=',$id)
                ->where(function ($query) use ($name,$shorten,$code) {
                    $query->where('name',$name)
                    ->orWhere('shorten',$shorten)
                    ->orWhere('code',$code);
                })->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                FundCluster::where('id', $id)
                ->update([
                    //'financing_id' => $fund_financing,
                    'name' => $name,
                    'shorten' => $shorten,
                    'code' => $code,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $fund_code = $code.mb_strtoupper($shorten);
                FundSource::where('fund_cluster_id', $id)
                ->update(['code' => DB::raw("CONCAT('$fund_code',UPPER(shorten))"),
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