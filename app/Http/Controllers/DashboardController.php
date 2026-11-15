<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_books' => Book::count(),
                'total_copies' => Book::sum('total_copies'),
                'available_copies' => Book::sum('available_copies'),
                'total_members' => User::where('role', 'member')->count(),
                'active_borrows' => BorrowRecord::where('status', 'borrowed')->count(),
                'overdue' => BorrowRecord::where('status', 'borrowed')->whereDate('due_date', '<', now())->count(),
            ];

            $recentBorrows = BorrowRecord::with(['user', 'book'])
                ->latest('borrowed_at')
                ->take(8)
                ->get();

            return view('dashboard.admin', compact('stats', 'recentBorrows'));
        }

        $activeBorrows = $user->borrowRecords()
            ->with('book')
            ->where('status', 'borrowed')
            ->orderBy('due_date')
            ->get();

        $borrowHistory = $user->borrowRecords()
            ->with('book')
            ->where('status', 'returned')
            ->latest('returned_at')
            ->take(5)
            ->get();

        return view('dashboard.member', compact('activeBorrows', 'borrowHistory'));
    }
}
