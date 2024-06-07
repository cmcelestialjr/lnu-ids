<?php

namespace App\Imports;

use App\Models\_PersonalInfo;
use App\Models\HRBillingList;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;

class GSISImport implements ToModel
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
     * @var array An array to store deduction IDs.
     */
    protected $deductionID;

    /**
     * GSISImport constructor.
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
        $this->deductionID = array();
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
        // Check if it's the first row (headers)
        if ($this->start === 0) {
            // Process the headers
            $this->processHeaders($row);
        } else {
            // Process data rows
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
        for ($i = 0; $i < count($row); $i++) {
            $header = $row[$i];

            // Create a new HRBillingList entry
            $insert = new HRBillingList();
            $insert->billing_id = $this->billing_id;
            $insert->name = mb_strtoupper($header);
            $insert->option = 'header';
            $insert->updated_by = $this->updated_by;
            $insert->save();

            // Check if a deduction should be created for the header
            if ($this->shouldCreateDeduction($i)) {
                // Find or create an HRDeduction entry
                $deduction = HRDeduction::firstOrCreate(
                    [
                        'group_id' => $this->group->id,
                        'name' => mb_strtoupper($header),
                    ],
                    [
                        'updated_by' => $this->updated_by,
                    ]
                );

                // Store the deduction ID for later use
                $this->deductionID[$i] = $deduction->id;
            } else {
                // No deduction should be created, so set the deduction ID to null
                $this->deductionID[$i] = null;
            }
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
        // Get personal information based on GSIS BP number
        $personalInfo = _PersonalInfo::where('gsis_bp_no', $row[0])->first();

        for ($i = 0; $i < count($row); $i++) {
            $deductionId = $this->deductionID[$i];

            if ($personalInfo !== null) {
                // Delete the deduction if the amount is less than or equal to 0
                if ($row[$i] <= 0) {
                    $delete = HRDeductionEmployee::where('user_id', $personalInfo->user_id)
                        ->where('payroll_type_id', $this->payroll_type)
                        ->where('deduction_id', $deductionId)
                        ->delete();

                    // Reset the auto-increment value of the table if a deletion occurs
                    if ($delete) {
                        DB::statement("ALTER TABLE `hr_deduction_employee` AUTO_INCREMENT = 0;");
                    }
                }

                // Process the deduction if necessary and the amount is greater than 0
                if ($this->shouldProcessDeduction($i) && $deductionId !== null && $row[$i] > 0) {
                    $getDeduction = HRDeduction::where('id', $deductionId)->first();

                    HRDeductionEmployee::updateOrCreate(
                        [
                            'user_id' => $personalInfo->user_id,
                            'payroll_type_id' => $this->payroll_type,
                            'deduction_id' => $deductionId,
                            'emp_stat_id' => $personalInfo->user->employee_default->emp_stat_id,
                        ],
                        [
                            'amount' => $row[$i],
                            'percent' => $getDeduction->percent,
                            'percent_employer' => $getDeduction->percent_employer,
                            'ceiling' => $getDeduction->ceiling,
                            'updated_by' => $this->updated_by,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            $insert = new HRBillingList();
            $insert->staff_no = $row[0];
            $insert->billing_id = $this->billing_id;
            $insert->deduction_id = $deductionId;
            $insert->amount = $row[$i];
            $insert->option = null;
            $insert->updated_by = $this->updated_by;
            $insert->save();
        }
    }

    /**
     * Check if a deduction should be created.
     *
     * @param int $header The header index.
     * @return bool
     */
    private function shouldCreateDeduction($header)
    {
        return $header === 10 || $header >= 13;
    }

    /**
     * Check if a deduction should be processed.
     *
     * @param int $value The value.
     * @return bool
     */
    private function shouldProcessDeduction($value)
    {
        return $value === 10 || $value >= 13;
    }
}
