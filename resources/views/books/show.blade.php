@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <a href="{{ route('books.index') }}" class="text-decoration-none small d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Back to catalog
    </a>

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <span class="badge bg-light text-dark border mb-2">{{ $book->category?->name ?? 'Uncategorized' }}</span>
                    <h1 class="h3 fw-bold mb-1">{{ $book->title }}</h1>
                    <p class="text-muted mb-0">by {{ $book->author }}</p>
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="btn-group">
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                        <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Delete this book from the catalog?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    </div>
                @endif
            </div>

            <hr>

            <div class="row g-4">
                <div class="col-md-8">
                    <h2 class="h6 fw-semibold">Description</h2>
                    <p class="text-muted">{{ $book->description ?: 'No description available for this title.' }}</p>

                    <dl class="row small mt-4">
                        <dt class="col-sm-3">ISBN</dt>
                        <dd class="col-sm-9">{{ $book->isbn }}</dd>
                        <dt class="col-sm-3">Published</dt>
                        <dd class="col-sm-9">{{ $book->published_year ?? 'Unknown' }}</dd>
                        <dt class="col-sm-3">Category</dt>
                        <dd class="col-sm-9">{{ $book->category?->name ?? 'Uncategorized' }}</dd>
                    </dl>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total copies</span>
                            <strong>{{ $book->total_copies }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">Available now</span>
                            <strong class="{{ $book->isAvailable() ? 'text-success' : 'text-danger' }}">{{ $book->available_copies }}</strong>
                        </div>

                        @unless (auth()->user()->isAdmin())
                            @if ($book->isAvailable())
                                <form method="POST" action="{{ route('borrows.store', $book) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100" style="background-color:#6f42c1;border-color:#6f42c1;">
                                        <i class="bi bi-journal-plus"></i> Borrow this book
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>No copies available</button>
                            @endif
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
