<?php

namespace App\Http\Controllers\SIMS\Information;

use App\Http\Controllers\Controller;
use App\Models\_FamilyBg;
use App\Models\FamRelations;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class FamBgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fam_relations = FamRelations::orderBy('id','ASC')->get();
        $data = array(
            'fam_relations' => $fam_relations
        );
        return view('sims/information/famNewModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;  

        DB::beginTransaction();
        try{
            $relation = $request->relation;
            $lastname = $request->lastname;
            $firstname = $request->firstname;
            $middlename = $request->middlename;
            $extname = $request->extname;
            $dob = date('Y-m-d',strtotime($request->dob));
            $contact = $request->contact;
            $email = $request->email;
            $occupation = $request->occupation;
            $employer = $request->employer;
            $employer_address = $request->employer_address;
            $employer_contact = $request->employer_contact;

            $check = _FamilyBg::where('lastname',$lastname)
                ->where('firstname',$firstname)
                ->where('dob',$dob)
                ->first();
            if($check){
                DB::rollback();
                return response()->json(['result' => 'exists']);
            }else{
                $insert = new _FamilyBg();
                $insert->user_id = $user_id;
                $insert->relation_id = $relation;
                $insert->lastname = $lastname;
                $insert->firstname = $firstname;
                $insert->middlename = $middlename;
                $insert->extname = $extname;
                $insert->dob = $dob;
                $insert->occupation = $occupation;
                $insert->contact_no = $contact;
                $insert->email = $email;
                $insert->employer = $employer;
                $insert->employer_address = $employer_address;                
                $insert->employer_contact = $employer_contact;
                $insert->updated_by = $user_id;
                $insert->save();
            }        

            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $fam_bg = _FamilyBg::with('fam_relation')
            ->where('user_id',$user_id)
            ->orderBy('relation_id','ASC')
            ->get();

        $data = array(    
            'fam_bg' => $fam_bg
        );
        return view('sims/information/informationFamBg',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $fam_bg = _FamilyBg::with('fam_relation')
            ->where('user_id',$user_id)
            ->where('id',$id)
            ->first();

        if($fam_bg==NULL){
            return view('layouts/error/404');
        }

        $fam_relations = FamRelations::orderBy('id','ASC')->get();
        
        $data = array(
            'id' => $id,
            'fam_relations' => $fam_relations,            
            'fam_bg' => $fam_bg
        );
        return view('sims/information/famEditModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;  

        $id = $request->id;

        $check = _FamilyBg::where('id',$id)
            ->where('user_id',$user_id)
            ->first();

        if($check==NULL){
            return response()->json(['result' => 'error']);
        }

        DB::beginTransaction();
        try{            
            $relation = $request->relation;
            $lastname = $request->lastname;
            $firstname = $request->firstname;
            $middlename = $request->middlename;
            $extname = $request->extname;
            $dob = date('Y-m-d',strtotime($request->dob));
            $contact = $request->contact;
            $email = $request->email;
            $occupation = $request->occupation;
            $employer = $request->employer;
            $employer_address = $request->employer_address;
            $employer_contact = $request->employer_contact;

            $check = _FamilyBg::where('lastname',$lastname)
                ->where('firstname',$firstname)
                ->where('dob',$dob)
                ->where('user_id',$user_id)
                ->where('id','<>',$id)
                ->first();
            if($check){
                DB::rollback();
                return response()->json(['result' => 'exists']);
            }else{
                // Update the program details in the database
                _FamilyBg::where('id', $id)
                ->update([
                    'relation_id' => $relation,
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'extname' => $extname,
                    'dob' => $dob,
                    'occupation' => $occupation,
                    'contact_no' => $contact,
                    'email' => $email,
                    'employer' => $employer,
                    'employer_address' => $employer_address,
                    'employer_contact' => $employer_contact,
                    'updated_by' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }        

            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    /**
     * Show the reponse for delete the specified resource.
     */
    public function delete(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $fam_bg = _FamilyBg::where('id',$id)
            ->where('user_id',$user_id)
            ->first();

        if($fam_bg==NULL){
            return view('layouts/error/404');
        }

        $data = array(
            'id' => $id
        );
        return view('sims/information/famDeleteModal',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $check = _FamilyBg::where('id',$id)
            ->where('user_id',$user_id)
            ->first();
        // Check if exists
        if ($check==NULL) {
            return response()->json(['result' => 'Error!']);
        }

        try{

            $delete = _FamilyBg::where('id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE _family_bg AUTO_INCREMENT = 0;");

            // Set the result to 'success'
            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'relation' => 'required|numeric',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'extname' => 'nullable|string',
            'dob' => 'required|date',
            'contact' => 'nullable|string',
            'email' => 'nullable|email:rfc,dns',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'employer_address' => 'nullable|string',
            'employer_contact' => 'nullable|string'
        ];

        $customMessages = [
            'relation.required' => 'Relation is required',
            'relation.numeric' => 'Relation must be a number',
            'lastname.required' => 'Lastname is required',
            'lastname.string' => 'Lastname must be a string',
            'firstname.required' => 'Firstname is required',
            'firstname.string' => 'Firstname must be a string',
            'middlename.string' => 'Middlename must be a string',
            'extname.string' => 'Extname must be a string',
            'dob.string' => 'Date of Birth must be a string',
            'contact.string' => 'Contact must be a string',
            'email.string' => 'Email must be valid',
            'occupation.string' => 'Occupation must be a string',
            'employer.string' => 'Employer must be a string',
            'employer_address.string' => 'Employer Address must be a string',
            'employer_contact.string' => 'Employer Contact must be a string',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function updateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'relation' => 'required|numeric',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'extname' => 'nullable|string',
            'dob' => 'required|date',
            'contact' => 'nullable|string',
            'email' => 'nullable|email:rfc,dns',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'employer_address' => 'nullable|string',
            'employer_contact' => 'nullable|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'relation.required' => 'Relation is required',
            'relation.numeric' => 'Relation must be a number',
            'lastname.required' => 'Lastname is required',
            'lastname.string' => 'Lastname must be a string',
            'firstname.required' => 'Firstname is required',
            'firstname.string' => 'Firstname must be a string',
            'middlename.string' => 'Middlename must be a string',
            'extname.string' => 'Extname must be a string',
            'dob.string' => 'Date of Birth must be a string',
            'contact.string' => 'Contact must be a string',
            'email.string' => 'Email must be valid',
            'occupation.string' => 'Occupation must be a string',
            'employer.string' => 'Employer must be a string',
            'employer_address.string' => 'Employer Address must be a string',
            'employer_contact.string' => 'Employer Contact must be a string',
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
        DB::rollback();
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
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
