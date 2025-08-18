<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function collection()
    {
        $query = User::select('id', 'name', 'email', 'visible');

        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (isset($this->filters['visible']) && $this->filters['visible'] != 1) {
            $query->where('visible', $this->filters['visible']);
        }

        return $query->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Visible',
        ];
    }
}
