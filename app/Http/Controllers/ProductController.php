<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Http\Requests\ProdukRequest;
use App\Models\Product;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('pages.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdukRequest $request)
    {
        Product::create($request->validated());

        Alert::success('Berhasil', 'Produk berhasil ditambahkan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('produk.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $produk)
    {
        return view('pages.produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdukRequest $request, Product $produk)
    {
        $produk->update($request->validated());

        Alert::success('Berhasil', 'Produk berhasil diperbarui.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('produk.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produk)
    {
        $produk->delete();

        Alert::success('Berhasil', 'Produk berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('produk.index');
    }
}
