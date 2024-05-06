<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\BillingImport;
use App\Imports\DataImport;
use App\Imports\DTRImport;
use App\Models\HRBilling;
use App\Models\HRBillingList;
use App\Models\HRDeductionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // Import data using DataImport class
        //Excel::import(new DataImport, $request->file('file')->store('temp'));
        Excel::import(new DTRImport, $request->file('file')->store('temp'));
        return back();
    }

    public function billing(Request $request)
    {
        $user = Auth::user();
        $updated_by = $user->id;

        // Retrieve HRDeductionGroup based on the provided group ID
        $group = HRDeductionGroup::where('id', $request->group)->first();

        if ($group) {
            // Get or create the billing and retrieve the billing ID
            $billing_id = $this->getOrCreateBilling($request, $updated_by);

            // Specify the sheet name or index you want to import
            $sheet = $group->name;
            // Import data using BillingImport class
            $import = new BillingImport($group, $billing_id, $request->payroll_type, $updated_by);
            $import->onlySheets($sheet);
            Excel::import($import, $request->file('file')->store('temp'));
        }

        return back();
    }

    private function getOrCreateBilling($request, $updated_by)
    {
        // Check if the billing already exists based on group, year, and month
        $billing = HRBilling::where('group_id', $request->group)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();

        if ($billing) {
            // If the billing exists, delete related billing list entries and update the billing
            $delete = HRBillingList::where('billing_id', $billing->id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_billing_list` AUTO_INCREMENT = 0;");
            $billing->updated_by = $updated_by;
            $billing->updated_at = date('Y-m-d H:i:s');
            $billing->save();
            $billing_id = $billing->id;
        } else {
            // If the billing doesn't exist, create a new billing entry
            $insert = new HRBilling();
            $insert->group_id = $request->group;
            $insert->year = $request->year;
            $insert->month = $request->month;
            $insert->updated_by = $updated_by;
            $insert->save();
            $billing_id = $insert->id;
        }

        return $billing_id;
    }
}
?>
