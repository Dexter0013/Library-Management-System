@extends('layouts.app')

@section('title', 'Catalog')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 fw-bold mb-0">Book Catalog</h1>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('books.create') }}" class="btn btn-primary" style="background-color:#6f42c1;border-color:#6f42c1;">
                <i class="bi bi-plus-lg"></i> Add Book
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="card p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Title, author, or ISBN" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Category</label>
                <select name="category" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-4">
                    <input type="checkbox" class="form-check-input" id="available_only" name="available_only" value="1" @checked(request('available_only'))>
                    <label class="form-check-label small" for="available_only">Available only</label>
                </div>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse ($books as $book)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light text-dark border">{{ $book->category?->name ?? 'Uncategorized' }}</span>
                            @if ($book->isAvailable())
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Out of stock</span>
                            @endif
                        </div>
                        <h2 class="h6 fw-bold mb-1">
                            <a href="{{ route('books.show', $book) }}" class="text-decoration-none text-dark">{{ $book->title }}</a>
                        </h2>
                        <p class="text-muted small mb-2">by {{ $book->author }}</p>
                        <p class="small mb-3 text-muted">{{ $book->available_copies }} / {{ $book->total_copies }} copies available</p>
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card p-5 text-center text-muted">
                    <i class="bi bi-inboxes fs-1 mb-2"></i>
                    <p class="mb-0">No books match your search.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $books->links('pagination::bootstrap-5') }}
    </div>
@endsection
