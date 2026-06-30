@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-box text-primary"></i> Products
        </h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="align-middle">
                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                            </td>
                            <td class="align-middle">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50"
                                        class="rounded object-fit-cover"
                                        onerror="this.src='https://via.placeholder.com/50'">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center
                                        justify-content-center text-white"
                                            style="width:50px;height:50px">
                                            <i class="fas fa-image"></i>
                                        </div>
                                @endif
                            </td>
                            <td class="align-middle fw-semibold">{{ $product->name }}</td>
                            <td class="align-middle">
                                <span class="badge bg-info text-dark">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="align-middle text-success fw-semibold">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="align-middle">
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('ច្បាស់ជាលុប?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No products yet.

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="mt-3">
    {{ $products->links() }}
</div> --}}

    {{-- Bootstrap Pagination --}}
    @if ($products->hasPages())
        <nav class="mt-3">
            <ul class="pagination pagination-sm">

                {{-- Previous --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}">
                            Previous
                        </a>
                    </li>
                @endif

                {{-- Pages --}}
                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next --}}
                @if ($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                @endif

            </ul>
        </nav>
    @endif
@endsection
