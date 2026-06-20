@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Foto Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product-gallery.index') }}">Foto Produk</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body pt-4">
                        <h5 class="card-title">Form Edit Foto Produk</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('product-gallery.update', $productGallery->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="products_id" class="form-label fw-semibold">
                                    Pilih Produk <span class="text-danger">*</span>
                                </label>
                                <select name="products_id" id="products_id" class="form-select @error('products_id') is-invalid @enderror">
                                    <option value="" disabled>-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('products_id', $productGallery->products_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('products_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold d-block">Foto Saat Ini</label>
                                <img src="{{ asset('storage/' . $productGallery->url) }}" alt="Preview" style="max-height: 200px; max-width: 100%;" class="img-thumbnail mb-2">
                            </div>

                            <div class="mb-3">
                                <label for="url" class="form-label fw-semibold">
                                    Ganti Foto <span class="text-secondary">(Kosongkan jika tidak ingin diubah)</span>
                                </label>
                                <input type="file" id="url" name="url" class="form-control @error('url') is-invalid @enderror" accept="image/*">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    Deskripsi (Opsional)
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Masukkan deskripsi foto jika ada">{{ old('description', $productGallery->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('product-gallery.index') }}" class="btn btn-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
