@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-list text-primary"></i> Categories
        </h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
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
                        <th>Products</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="align-middle">
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" width="50" height="50"
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
                            <td class="fw-semibold align-middle">{{ $category->name }}</td>
                            <td class="align-middle">
                                <span class="badge bg-primary">
                                    {{ $category->products->count() }} products
                                </span>
                            </td>
                            <td class="align-middle text-muted small">
                                {{ $category->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                មិនទាន់មាន Category ទេ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    {{-- <div class="mt-3">
    {{ $categories->links() }}
</div> --}}

    {{-- Bootstrap Pagination --}}
    @if ($categories->hasPages())
        <nav class="mt-3">
            <ul class="pagination pagination-sm">

                {{-- Previous --}}
                @if ($categories->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $categories->previousPageUrl() }}">
                            Previous
                        </a>
                    </li>
                @endif

                {{-- Pages --}}
                @for ($i = 1; $i <= $categories->lastPage(); $i++)
                    <li class="page-item {{ $categories->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next --}}
                @if ($categories->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $categories->nextPageUrl() }}">Next</a>
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
