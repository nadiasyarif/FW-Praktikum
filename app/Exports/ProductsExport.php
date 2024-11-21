<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('id', 'product_name', 'unit', 'type', 'information', 'qty', 'producer')->get();
    }

    /**
     * Define the headings for the Excel sheet.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Unit',
            'Type',
            'Information',
            'Qty',
            'Producer'
        ];
    }
}
