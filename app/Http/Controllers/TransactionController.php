<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionDataTable;
use App\Http\Requests\TransaksiRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('pages.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->roles !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users    = User::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();
        return view('pages.transaction.create', compact('users', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransaksiRequest $request)
    {
        if (auth()->user()->roles !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validated();
        $products = $data['products_id'];
        unset($data['products_id']);

        $transaction = Transaction::create($data);

        foreach ($products as $productId) {
            TransactionItem::create([
                'transactions_id' => $transaction->id,
                'products_id'     => $productId,
                'users_id'        => $data['users_id'],
            ]);
        }

        Alert::success('Berhasil', 'Transaksi berhasil ditambahkan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('transaction.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if (auth()->user()->roles !== 'admin' && $transaction->users_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load(['items.product.galleries', 'user']);

        return view('pages.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if (auth()->user()->roles !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users    = User::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();
        $transactionProducts = $transaction->items->pluck('products_id')->toArray();

        return view('pages.transaction.edit', compact('transaction', 'users', 'products', 'transactionProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransaksiRequest $request, Transaction $transaction)
    {
        if (auth()->user()->roles !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validated();
        $products = $data['products_id'] ?? [];
        unset($data['products_id']);

        $transaction->update($data);

        if ($request->has('products_id')) {
            $transaction->items()->delete();
            foreach ($products as $productId) {
                TransactionItem::create([
                    'transactions_id' => $transaction->id,
                    'products_id'     => $productId,
                    'users_id'        => $data['users_id'],
                ]);
            }
        }

        Alert::success('Berhasil', 'Transaksi berhasil diperbarui.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if (auth()->user()->roles !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $transaction->delete();

        Alert::success('Berhasil', 'Transaksi berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('transaction.index');
    }
}
