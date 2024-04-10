<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function employee(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $roleFilter = function ($query) {
            $query->whereIn('role_id', [2,3]);
        };
       // with(['user_role' => $roleFilter])->whereHas('user_role', $roleFilter)
       
       $data = [];

        // Validate the incoming request data
        $validator = $this->validateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $results = Users::where('id_no','!=',NULL)
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw("CONCAT(lastname,', ',firstname)"), 'LIKE', "%$search%");
                        $query->orWhere('id_no', 'LIKE', "%$search%");
                    })                    
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->limit(10)
                    ->get();
        
        if($results->count()>0){
            foreach ($results as $result) {
                $employee = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $employee.'-'.$result->id_no];
            }
        }
        return response()->json($data);
    }
    public function designation(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $roleFilter = function ($query) {
            $query->whereIn('role_id', [2,3]);
        };

        $data = [];

        // Validate the incoming request data
        $validator = $this->validateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $results = Users::where('id_no','!=',NULL)
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw("CONCAT(lastname,', ',firstname)"), 'LIKE', "%$search%");
                        $query->orWhere('id_no', 'LIKE', "%$search%");
                    })                    
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->limit(10)
                    ->get();
        
        if($results->count()>0){
            foreach ($results as $result) {
                $employee = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $employee.'-'.$result->id_no];
            }
        }
        return response()->json($data);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function validateRequest($request)
    {
        $rules = [
            'search' => 'nullable|string',
        ];
        
        $customMessages = [
            'search.string' => 'Search must be a string',
        ];

        // Sanitize the 'search' parameter by removing HTML tags and trimming whitespace
        $sanitizedData = $request->all();

        if (isset($sanitizedData['search'])) {
            $sanitizedData['search'] = strip_tags(trim($sanitizedData['search']));
        }

        return Validator::make($sanitizedData, $rules, $customMessages);
    }
}

