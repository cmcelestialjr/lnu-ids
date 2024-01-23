<?php

namespace App\Imports;

use App\Models\_Work;
use App\Models\EmploymentStatus;
use App\Models\Users;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class HRISEmployeeCareerImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        $check = Users::where('id_no',$row["id_no"])->first();
        if($check!=NULL){
            $user = Auth::User();
            $updated_by = $user->id;

            $date_from = $row['DateEffective'];
            if($date_from=='' || $date_from=='\N'){
                $date_from = NULL;
            }else{
                $date_from = date('Y-m-d',strtotime($date_from));
            }
           
            if(strtotime($row['EndEventDate'])){
                $date_to = date('Y-m-d', strtotime($row['EndEventDate']));
            }else{
                $date_to = 'present';
            }

            $salary = $row['CustomRate'];

            if($salary=='\N' || $salary==''){
                $salary = NULL;
            }

            $cause = $row['EndEventRemarks'];
            if($cause=='\N' || $cause==''){
                $cause = NULL;
            }

            $role_id = $this->isfaculty($row['PositionId']);
            $emp_stat_id = $this->get_emp_stat($row['EmploymentType']);
            $position_title = $this->position($row['PositionId']);
            $position_shorten = $this->position_shorten($row['PositionId']);
            $gov_service = $this->isGovService($emp_stat_id);
            $sg = $row['SalaryGrade'];
            $step = 1;
            $status = 1;
            $office = 'LNU';
            $lnu = 1;

            _Work::updateOrCreate(
                [
                    'user_id' => $check->id,
                    'date_from' => $date_from
                ],
                [
                    'role_id' => $role_id,
                    'emp_stat_id' => $emp_stat_id,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'position_title' => $position_title,
                    'position_shorten' => $position_shorten,
                    'office' => $office,
                    'salary' => $salary,
                    'sg' => $sg,
                    'step' => $step,
                    'status' => $status,
                    'lnu' => $lnu,
                    'gov_service' => $gov_service,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );
        }
    }

    private function get_emp_stat($emp_stat_id){
        $emp_stats = array(
            '1' => '2',
            '2' => '5',
            '3' => '4',
            '4' => '4',
            '5' => '3',
            '0' => '1',
        );
        if (array_key_exists($emp_stat_id, $emp_stats)) {
            return $emp_stats[$emp_stat_id];
        } else {
            return NULL;
        }
    }

    private function position($position_id){
        $positions = array(
            '21' => 'University President',
            '22' => 'Professor VI',
            '23' => 'Professor V',
            '24' => 'Professor IV',
            '25' => 'Professor III',
            '26' => 'Professor II',
            '27' => 'Professor I',
            '28' => 'Associate Professor VI',
            '29' => 'Associate Professor V',
            '30' => 'Associate Professor IV',
            '31' => 'Associate Professor III',
            '32' => 'Associate Professor II',
            '33' => 'Associate Professor I',
            '34' => 'Assistant Professor VI',
            '35' => 'Assistant Professor V',
            '36' => 'Assistant Professor IV',
            '37' => 'Assistant Professor III',
            '38' => 'Assistant Professor II',
            '39' => 'Assistant Professor I',
            '40' => 'Instructor VI',
            '41' => 'Instructor V',
            '42' => 'Instructor IV',
            '43' => 'Instructor III',
            '44' => 'Instructor II',
            '45' => 'Instructor I',
            '46' => 'SUC President III',
            '47' => 'Chief Admin Officer',
            '48' => 'Admin Officer VI',
            '49' => 'Admin Officer V',
            '50' => 'Admin Officer IV',
            '51' => 'Admin Officer III',
            '52' => 'Admin Officer II',
            '53' => 'Admin Officer I',
            '54' => 'Accountant III',
            '55' => 'Accountant II',
            '56' => 'Accountant I',
            '57' => 'Medical Officer III',
            '58' => 'Medical Officer II',
            '59' => 'Medical Officer I',
            '60' => 'Nurse III',
            '61' => 'Nurse II',
            '62' => 'Nurse I',
            '63' => 'Dentist II',
            '64' => 'Dentist I',
            '65' => 'Senior Admin Assistant II',
            '66' => 'Senior Admin Assistant I',
            '67' => 'Registrar III',
            '68' => 'Registrar II',
            '69' => 'Registrar I',
            '70' => 'Board Secretary III',
            '71' => 'College Librarian III',
            '72' => 'College Librarian II',
            '73' => 'College Librarian I',
            '74' => 'Librarian III',
            '75' => 'Librarian II',
            '76' => 'Librarian I',
            '77' => 'Science Research Specialist I',
            '78' => 'Education Program Specialist II',
            '79' => 'Information System Researcher III',
            '80' => 'Computer Programmer III',
            '81' => 'Computer Programmer II',
            '82' => 'Computer Programmer I',
            '83' => 'Admin Assistant VI',
            '84' => 'Admin Assistant V',
            '85' => 'Admin Assistant IV',
            '86' => 'Admin Assistant III',
            '87' => 'Admin Assistant II',
            '88' => 'Admin Assistant I',
            '89' => 'Admin Aide VI',
            '90' => 'Admin Aide V',
            '91' => 'Admin Aide IV',
            '92' => 'Admin Aide III',
            '93' => 'Admin Aide II',
            '94' => 'Admin Aide I',
            '95' => 'Dormitory Attendant III',
            '96' => 'Security Guard III',
            '97' => 'Security Guard II',
            '98' => 'Security Guard I',
            '99' => 'Librarian Aide',
            '100' => 'Laboratory Aide II',
            '101' => 'Laboratory Aide I',
            '102' => 'Guidance Counselor III',
            '103' => 'Guidance Counselor I',
            '104' => 'Dental Aide',
            '105' => 'University President',
            '106' => 'Chief Administrative Officer',
            '107' => 'Instructor I',
            '108' => 'Administrative Officer V (HRMO)',
            '109' => 'Chief Administrative Officer'
        );
        if (array_key_exists($position_id, $positions)) {
            return $positions[$position_id];
        } else {
            return NULL;
        }
    }

    private function position_shorten($position_id){

        $positions = array(
            '21' => 'UNIVPRES',
            '22' => 'PROFVI',
            '23' => 'PROFV',
            '24' => 'PROFIV',
            '25' => 'PROFIII',
            '26' => 'PROFII',
            '27' => 'PROFI',
            '28' => 'ASSOCPROFVI',
            '29' => 'ASSOCPROFV',
            '30' => 'ASSOCPROFIV',
            '31' => 'ASSOCPROFIII',
            '32' => 'ASSOCPROFII',
            '33' => 'ASSOCPROFI',
            '34' => 'ASSTPROFVI',
            '35' => 'ASSTPROFV',
            '36' => 'ASSTPROFIV',
            '37' => 'ASSTPROFIII',
            '38' => 'ASSTPROFII',
            '39' => 'ASSTPROFI',
            '40' => 'INSTVI',
            '41' => 'INSTV',
            '42' => 'INSTIV',
            '43' => 'INSTIII',
            '44' => 'INSTII',
            '45' => 'INSTI',
            '46' => 'SUCPRESIII',
            '47' => 'CAO',
            '48' => 'ADOFVI',
            '49' => 'ADOFV',
            '50' => 'ADOFIV',
            '51' => 'ADOFIII',
            '52' => 'ADOFII',
            '53' => 'ADOFI',
            '54' => 'ACCTIII',
            '55' => 'ACCTII',
            '56' => 'ACCTI',
            '57' => 'MEDOFIII',
            '58' => 'MEDOFII',
            '59' => 'MEDOFI',
            '60' => 'NurseIII',
            '61' => 'NurseII',
            '62' => 'NurseI',
            '63' => 'DentistII',
            '64' => 'DentistI',
            '65' => 'SADASII',
            '66' => 'SADASI',
            '67' => 'REGIII',
            '68' => 'REGII',
            '69' => 'REGI',
            '70' => 'BOARDSECIII',
            '71' => 'COLIBIII',
            '72' => 'COLIBII',
            '73' => 'COLIBI',
            '74' => 'LIBIII',
            '75' => 'LIBII',
            '76' => 'LIBI',
            '77' => 'SRCI',
            '78' => 'EPSII',
            '79' => 'ISRIII',
            '80' => 'COMPROGIII',
            '81' => 'COMPROGII',
            '82' => 'COMPROGI',
            '83' => 'ADASVI',
            '84' => 'ADASV',
            '85' => 'ADASIV',
            '86' => 'ADASIII',
            '87' => 'ADASII',
            '88' => 'ADASI',
            '89' => 'AAIDEVI',
            '90' => 'AAIDEV',
            '91' => 'AAIDEIV',
            '92' => 'AAIDEIII',
            '93' => 'AAIDEII',
            '94' => 'AAIDEI',
            '95' => 'DORMAIDEIII',
            '96' => 'SGIII',
            '97' => 'SGII',
            '98' => 'SGI',
            '99' => 'LIBAIDE',
            '100' => 'LABAIDEII',
            '101' => 'LABAIDEI',
            '102' => 'GCIII',
            '103' => 'GCI',
            '104' => 'DENAIDE',
            '105' => 'UNIVPRES',
            '106' => 'CAO',
            '107' => 'INSTI',
            '108' => 'ADOFV',
            '109' => 'CAO'
        );
        if (array_key_exists($position_id, $positions)) {
            return $positions[$position_id];
        } else {
            return NULL;
        }
    }

    private function isfaculty($position_id){
        $isfaculty = array(
            '21' => '0',
            '22' => '1',
            '23' => '1',
            '24' => '1',
            '25' => '1',
            '26' => '1',
            '27' => '1',
            '28' => '1',
            '29' => '1',
            '30' => '1',
            '31' => '1',
            '32' => '1',
            '33' => '1',
            '34' => '1',
            '35' => '1',
            '36' => '1',
            '37' => '1',
            '38' => '1',
            '39' => '1',
            '40' => '1',
            '41' => '1',
            '42' => '1',
            '43' => '1',
            '44' => '1',
            '45' => '1',
            '46' => '0',
            '47' => '0',
            '48' => '0',
            '49' => '0',
            '50' => '0',
            '51' => '0',
            '52' => '0',
            '53' => '0',
            '54' => '0',
            '55' => '0',
            '56' => '0',
            '57' => '0',
            '58' => '0',
            '59' => '0',
            '60' => '0',
            '61' => '0',
            '62' => '0',
            '63' => '0',
            '64' => '0',
            '65' => '0',
            '66' => '0',
            '67' => '0',
            '68' => '0',
            '69' => '0',
            '70' => '0',
            '71' => '0',
            '72' => '0',
            '73' => '0',
            '74' => '0',
            '75' => '0',
            '76' => '0',
            '77' => '0',
            '78' => '0',
            '79' => '0',
            '80' => '0',
            '81' => '0',
            '82' => '0',
            '83' => '0',
            '84' => '0',
            '85' => '0',
            '86' => '0',
            '87' => '0',
            '88' => '0',
            '89' => '0',
            '90' => '0',
            '91' => '0',
            '92' => '0',
            '93' => '0',
            '94' => '0',
            '95' => '0',
            '96' => '0',
            '97' => '0',
            '98' => '0',
            '99' => '0',
            '100' => '0',
            '101' => '0',
            '102' => '0',
            '103' => '0',
            '104' => '0',
            '105' => '0',
            '106' => '0',
            '107' => '0',
            '108' => '0',
            '109' => '0',
        );
        if (array_key_exists($position_id, $isfaculty)) {
            return $isfaculty[$position_id];
        } else {
            return NULL;
        }
    }
    private function isGovService($emp_stat_id){
        $gov_service = EmploymentStatus::find($emp_stat_id);
        if($gov_service){
            return $gov_service->gov;
        }else{
            return 'N';
        }
    }
}