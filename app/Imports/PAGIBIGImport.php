<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\HRBillingList;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class PAGIBIGImport implements ToModel
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
         // Check if the second column has a value
        if ($row[1]) {
            // Determine the appropriate column name based on the value in the second column
            if ($row[1] == 'MPL') {
                $pagibig_no = 'pagibig_mpl_app_no';
            } elseif ($row[1] == 'MP2') {
                $pagibig_no = 'pagibig2_no';
            } elseif ($row[1] == 'CAL') {
                $pagibig_no = 'pagibig_cal_app_no';
            } elseif ($row[1] == 'HOUSING') {
                $pagibig_no = 'pagibig_housing_app_no';
            }else{
                $pagibig_no = NULL;
            }

            if($pagibig_no){
                // Get the deduction ID based on the column name and value in the second column
                $deductionId = $this->shouldCreateDeduction($row[1]);

                // Find the personal information using the appropriate column name and value in the first column
                $personalInfo = _PersonalInfo::where($pagibig_no, $row[0])->first();

                // If personal information is found
                if ($personalInfo !== null) {
                    // Check if the amount in the third column is greater than 0
                    if ($row[2]) {
                        // Update or create an HRDeductionEmployee record based on the user, payroll type, and deduction ID
                        HRDeductionEmployee::updateOrCreate(
                            [
                                'user_id' => $personalInfo->user_id,
                                'payroll_type_id' => $this->payroll_type,
                                'deduction_id' => $deductionId,
                            ],
                            [
                                'emp_stat_id' => $personalInfo->user->employee_default->emp_stat_id,
                                'amount' => $row[2],
                                'updated_by' => $this->updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
            }
        }

        if($deductionId){
            // Create a new HRBillingList record with the data from the row
            $insert = new HRBillingList();
            $insert->staff_no = $row[0];
            $insert->billing_id = $this->billing_id;
            $insert->deduction_id = $deductionId;
            $insert->name = $row[1];
            $insert->amount = $row[2];
            $insert->option = null;
            $insert->updated_by = $this->updated_by;
            $insert->save();
        }
    }

    /**
     * Check if the deduction should be created or retrieved.
     *
     * @param string $deductionName
     * @return int
     */
    private function shouldCreateDeduction($deductionName)
    {
        // Check if the deduction already exists based on group ID and name
        $deduction = HRDeduction::firstOrCreate(
            [
                'group_id' => $this->group->id,
                'name' => mb_strtoupper($deductionName),
            ],
            [
                'updated_by' => $this->updated_by,
            ]
        );
        // Return the ID of the deduction
        return $deduction->id;
    }
}
