@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0">Here's what's happening in the library.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Titles</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total_books'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Total Copies</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total_copies'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Available</div>
                <div class="h4 fw-bold mb-0 text-success">{{ $stats['available_copies'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Members</div>
                <div class="h4 fw-bold mb-0">{{ $stats['total_members'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Active Loans</div>
                <div class="h4 fw-bold mb-0 text-primary">{{ $stats['active_borrows'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card p-3 h-100">
                <div class="text-muted small">Overdue</div>
                <div class="h4 fw-bold mb-0 text-danger">{{ $stats['overdue'] }}</div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('books.create') }}" class="btn btn-primary" style="background-color:#6f42c1;border-color:#6f42c1;"><i class="bi bi-plus-lg"></i> Add Book</a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-tags"></i> Manage Categories</a>
        <a href="{{ route('admin.borrows.index') }}" class="btn btn-outline-secondary"><i class="bi bi-journal-check"></i> View All Loans</a>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-semibold">Recent Activity</div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Borrowed</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBorrows as $borrow)
                        <tr>
                            <td>{{ $borrow->user->name }}</td>
                            <td>{{ $borrow->book->title }}</td>
                            <td>{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td>{{ $borrow->due_date->format('M d, Y') }}</td>
                            <td>
                                @if ($borrow->status === 'returned')
                                    <span class="badge bg-success badge-status">Returned</span>
                                @elseif ($borrow->isOverdue())
                                    <span class="badge bg-danger badge-status">Overdue</span>
                                @else
                                    <span class="badge bg-primary badge-status">Borrowed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No borrowing activity yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
