<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class BillingImport implements WithMultipleSheets 
{
    use WithConditionalSheets;

    /**
     * @var int The billing ID.
     */
    protected $sheet;

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

    public function __construct($group, $billing_id, $payroll_type, $updated_by)
    {
        $this->group = $group;
        $this->billing_id = $billing_id;
        $this->payroll_type = $payroll_type;
        $this->updated_by = $updated_by;
    }
   
    public function conditionalSheets(): array
    {
        return [
            'GSIS' => new GSISImport($this->group, $this->billing_id, $this->payroll_type, $this->file, $this->updated_by),
            'PAGIBIG' => new PAGIBIGImport($this->group, $this->billing_id, $this->payroll_type, $this->file, $this->updated_by),
        ];
    }
}

?>