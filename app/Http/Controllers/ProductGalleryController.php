<?php

namespace App\Http\Controllers;

use App\DataTables\ProductGalleryDataTable;
use App\Http\Requests\ProdukGalleryRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductGalleryDataTable $dataTable)
    {
        return $dataTable->render('pages.produkgallery.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('pages.produkgallery.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdukGalleryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('url')) {
            foreach ($request->file('url') as $file) {
                ProductGallery::create([
                    'products_id' => $data['products_id'],
                    'url'         => $file->store('gallery', 'public'),
                    'description' => $data['description'] ?? null,
                ]);
            }
        }

        Alert::success('Berhasil', 'Foto produk berhasil ditambahkan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('product-gallery.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductGallery $productGallery)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductGallery $productGallery)
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('pages.produkgallery.edit', compact('productGallery', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdukGalleryRequest $request, ProductGallery $productGallery)
    {
        $data = $request->validated();

        if ($request->hasFile('url')) {
            // Delete old file
            if ($productGallery->url) {
                Storage::disk('public')->delete($productGallery->url);
            }
            // Store new file
            $data['url'] = $request->file('url')->store('gallery', 'public');
        } else {
            // If no new file uploaded, remove url from update array to preserve old value
            unset($data['url']);
        }

        $productGallery->update($data);

        Alert::success('Berhasil', 'Foto produk berhasil diperbarui.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('product-gallery.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductGallery $productGallery)
    {
        if ($productGallery->url) {
            Storage::disk('public')->delete($productGallery->url);
        }

        $productGallery->delete();

        Alert::success('Berhasil', 'Foto produk berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('product-gallery.index');
    }
}
