<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowController extends Controller
{
    public function index(Request $request): View
    {
        $borrows = $request->user()->borrowRecords()
            ->with('book')
            ->latest('borrowed_at')
            ->paginate(10);

        return view('borrows.index', compact('borrows'));
    }

    public function adminIndex(Request $request): View
    {
        $query = BorrowRecord::query()->with(['user', 'book']);

        if ($status = $request->input('status')) {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')->whereDate('due_date', '<', now());
            } else {
                $query->where('status', $status);
            }
        }

        $borrows = $query->latest('borrowed_at')->paginate(15)->withQueryString();

        return view('borrows.admin_index', compact('borrows'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        if (! $book->isAvailable()) {
            return back()->with('error', 'No copies of this book are currently available.');
        }

        $alreadyBorrowed = $request->user()->borrowRecords()
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'You already have this book on loan.');
        }

        BorrowRecord::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'borrowed',
        ]);

        $book->decrement('available_copies');

        return back()->with('success', "You've borrowed \"{$book->title}\". It's due back in 14 days.");
    }

    public function return(BorrowRecord $borrow): RedirectResponse
    {
        $user = request()->user();

        if (! $user->isAdmin() && $borrow->user_id !== $user->id) {
            abort(403);
        }

        if ($borrow->status !== 'borrowed') {
            return back()->with('error', 'This book has already been returned.');
        }

        $borrow->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        $borrow->book()->increment('available_copies');

        return back()->with('success', "\"{$borrow->book->title}\" marked as returned.");
    }
}
