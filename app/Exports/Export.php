<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Export implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'stud_id',
            'surname',
            'first_name',
            'middle_name',
            'qualifier',
            'gender',
            'date_of_birth',
            'course',
            'year_level',
            'father_lastname',
            'father_firstname',
            'father_middlename',
            'mother_lastname',
            'mother_firstname',
            'mother_middlename',
            'address',
            'zip',
            'phone_no',
            'email'
        ];
    }
}