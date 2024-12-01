<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use App\Models\Suppliers;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('supplier');

        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate(5);
        return view("master-data.product-master.index-product", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Suppliers::all();
        return view('master-data.product-master.create-product', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input data
        $validasi_data = $request->validate([
            'product_name'=> 'required|string|max:255',
            'unit'=> 'required|string|max:50',
            'type'=> 'required|string|max:50',
            'information'=> 'nullable|string',
            'qty'=> 'required|integer',
            'producer'=> 'required|string|max:255',
            'supplier_id'=> 'required|exists:suppliers,id',
        ]);

        // proses simpan data kedalam database
        Product::create($validasi_data);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view("master-data.product-master.detail-product", compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('master-data.product-master.edit-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_name'=> 'required|string|max:255',
            'unit'=> 'required|string|max:50',
            'type'=> 'required|string|max:50',
            'information'=> 'nullable|string',
            'qty'=> 'required|integer',
            'producer'=> 'required|string|max:255',
            'supplier_id'=> 'required|exists:suppliers,id',
        ]);
        $product = Product::findOrFail($id);
        $product->update([
            'product_name'=> $request->product_name,
            'unit'=> $request->unit,
            'type'=> $request->type,
            'information'=> $request->information,
            'qty'=> $request->qty,
            'producer'=> $request->producer,
            'supplier_id'=> $request->id,
        ]);
        return redirect()->back()->with('success', 'Product update successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product){
            $product->delete();
            return redirect()->back()->with('success','Product Berhasil di Hapus');
        }
        return redirect()->back()->with('error','Product TidaK Ditemukan');
    }

    public function exportExcel ()
    {
        return Excel::download(new ProductsExport, 'product.xlsx');
    }

    public function exportToPDF()
    {
        $export = new ProductsExport();
        $data = $export->collection();

        $html = '<h1>Products List</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">';
        $html .= '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Information</th>
                        <th>Qty</th>
                        <th>Producer</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';
        foreach ($data as $product) {
            $html .= '<tr>
                        <td>' . $product->id . '</td>
                        <td>' . $product->product_name . '</td>
                        <td>' . $product->unit . '</td>
                        <td>' . $product->type . '</td>
                        <td>' . $product->information . '</td>
                        <td>' . $product->qty . '</td>
                        <td>' . $product->producer . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);

        return $pdf->download('products.pdf');
    }
}
