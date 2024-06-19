<?php

namespace App\Imports;

use App\Models\HRBillingList;
use App\Models\HRDeduction;
use App\Models\HRDeductionDocs;
use App\Models\HRDeductionEmployee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class CSBImport implements ToModel
{
    /**
     * @var mixed The group object.
     */
    protected $group;

    /**
     * @var int The billing ID.
     */
    protected $billing_id;

    /**
     * @var string The payroll type.
     */
    protected $payroll_type;

    /**
     * @var string The file path.
     */
    protected $file;

    /**
     * @var int The ID of the user who updated the data.
     */
    protected $updated_by;

    /**
     * @var int The starting index.
     */
    protected $start;

    /**
     * PAGIBIGImport constructor.
     *
     * @param mixed $group The group object.
     * @param int $billing_id The billing ID.
     * @param string $payroll_type The payroll type.
     * @param string $file The file path.
     * @param int $updated_by The ID of the user who updated the data.
     */
    public function __construct($group, $billing_id, $payroll_type, $file, $updated_by)
    {
        $this->group = $group;
        $this->billing_id = $billing_id;
        $this->payroll_type = $payroll_type;
        $this->file = $file;
        $this->updated_by = $updated_by;
        $this->start = 0;
    }

    /**
     * Get the heading row index.
     *
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Get the starting row index.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Process each row of data.
     *
     * @param array $row The row data.
     * @return void
     */
    public function model(array $row)
    {
        // Check if it's the first row (header row) or data row
        if ($this->start === 0) {
            // Process the header row
            $this->processHeaders($row);
        } else {
            // Process the data row
            $this->processDataRows($row);
        }

        // Increment the start counter
        $this->start++;
    }

    /**
     * Process the header row.
     *
     * @param array $row The header row data.
     * @return void
     */
    private function processHeaders(array $row)
    {
        // Iterate through each column in the row
        for ($i = 0; $i < count($row); $i++) {
            // Get the header value from the current column
            $header = $row[$i];

            // Create a new HRBillingList record with the header information
            $insert = new HRBillingList();
            $insert->billing_id = $this->billing_id;
            $insert->name = mb_strtoupper($header);
            $insert->option = 'header';
            $insert->updated_by = $this->updated_by;
            $insert->save();
        }
    }

    /**
     * Process data rows.
     *
     * @param array $row The row data.
     * @return void
     */
    private function processDataRows(array $row)
    {
        $deductionId = NULL;
        $user_id = NULL;
        $status = NULL;
         // Check if the second column has a value

        //if ($row[0]) {
            // Get the deduction ID based on the column name and value in the second column
            $deductionId = $this->shouldCreateDeduction();
           // dd($deductionId);
            // Find the personal information using the appropriate column name and value in the first column
            $employee = HRDeductionDocs::where('account_no', $row[0])
                ->first();

            // If personal information is found
            if ($employee) {
                // Check if the amount in the third column is greater than 0
                if ($row[2]) {
                    // Update or create an HRDeductionDocs record based on the user, payroll type, and deduction ID
                    $user_id = $employee->employee->user_id;

                    HRDeductionDocs::updateOrCreate(
                        [
                            'account_no' => $row[0],
                        ],
                        [
                            'date_from' => Carbon::create(1899, 12, 30)->addDays($row[4])->format('Y-m-d'),
                            'date_to' => Carbon::create(1899, 12, 30)->addDays($row[5])->format('Y-m-d'),
                            'amount' => $row[2],
                            'total_amount' => $row[3],
                            'updated_by' => $this->updated_by,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                    $total_deduction = HRDeductionDocs::where('date_to','>=',date('Y-m-01'))
                        ->where('deduction_employee_id',$employee->deduction_employee_id)
                        ->sum('amount');
                    $update = HRDeductionEmployee::find($employee->deduction_employee_id);
                    $update->amount = $total_deduction;
                    $update->save();
                    $status = 1;
                }
            }


            if($deductionId){
                // Create a new HRBillingList record with the data from the row
                $insert = new HRBillingList();
                $insert->billing_id = $this->billing_id;
                $insert->user_id = $user_id;
                $insert->staff_no = $row[0];
                $insert->deduction_id = $deductionId;
                $insert->name = $row[1];
                $insert->amount = $row[2];
                $insert->total_amount = $row[3];
                $insert->date_from = Carbon::create(1899, 12, 30)->addDays($row[4])->format('Y-m-d');
                $insert->date_to = Carbon::create(1899, 12, 30)->addDays($row[5])->format('Y-m-d');
                $insert->option = null;
                $insert->status = $status;
                $insert->updated_by = $this->updated_by;
                $insert->save();
            }
        //}
    }

    /**
     * Check if the deduction should be created or retrieved.
     *
     * @param string $deductionName
     * @return int
     */
    private function shouldCreateDeduction()
    {
        // Check if the deduction already exists based on group ID and name
        $deduction = HRDeduction::firstOrCreate(
            [
                'group_id' => $this->group->id,
                'name' => 'LOAN',
            ],
            [
                'updated_by' => $this->updated_by,
            ]
        );
        // Return the ID of the deduction
        return $deduction->id;
    }
}
