<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@library.test'],
            [
                'name' => 'Library Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $memberData = [
            ['name' => 'Sample Member', 'email' => 'nfeest@example.net'],
            ['name' => 'Emma Watson', 'email' => 'emma@library.test'],
            ['name' => 'John Doe', 'email' => 'john@library.test'],
            ['name' => 'Sarah Connor', 'email' => 'sarah@library.test'],
            ['name' => 'Bruce Wayne', 'email' => 'bruce@library.test'],
            ['name' => 'Peter Parker', 'email' => 'peter@library.test'],
        ];

        $members = collect($memberData)->map(function ($data) {
            return User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'member',
                    'email_verified_at' => now(),
                ]
            );
        });

        $categoriesData = [
            ['name' => 'Fiction', 'description' => 'Novels and short stories.'],
            ['name' => 'Science', 'description' => 'Popular science and technical reads.'],
            ['name' => 'History', 'description' => 'Books about the past.'],
            ['name' => 'Technology', 'description' => 'Programming and computing.'],
            ['name' => 'Biography', 'description' => 'Life stories of notable people.'],
        ];

        $categories = collect($categoriesData)->map(function ($data) {
            return Category::firstOrCreate(['name' => $data['name']], $data);
        });

        $booksData = [
            ['title' => 'The Silent Orchard', 'author' => 'Elena Marsh', 'isbn' => '9780000000001', 'category' => 'Fiction', 'copies' => 4, 'year' => 2018],
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'isbn' => '9780000000002', 'category' => 'Science', 'copies' => 3, 'year' => 1988],
            ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'isbn' => '9780000000003', 'category' => 'History', 'copies' => 5, 'year' => 2011],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '9780000000004', 'category' => 'Technology', 'copies' => 6, 'year' => 2008],
            ['title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'isbn' => '9780000000005', 'category' => 'Biography', 'copies' => 2, 'year' => 2011],
            ['title' => 'The Midnight Library', 'author' => 'Matt Haig', 'isbn' => '9780000000006', 'category' => 'Fiction', 'copies' => 3, 'year' => 2020],
            ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'isbn' => '9780000000007', 'category' => 'Science', 'copies' => 2, 'year' => 1980],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas', 'isbn' => '9780000000008', 'category' => 'Technology', 'copies' => 4, 'year' => 1999],
            ['title' => 'Guns, Germs, and Steel', 'author' => 'Jared Diamond', 'isbn' => '9780000000009', 'category' => 'History', 'copies' => 3, 'year' => 1997],
            ['title' => 'Educated', 'author' => 'Tara Westover', 'isbn' => '9780000000010', 'category' => 'Biography', 'copies' => 3, 'year' => 2018],
        ];

        $books = collect($booksData)->map(function ($data) use ($categories) {
            $catId = $categories->firstWhere('name', $data['category'])->id;
            return Book::firstOrCreate(
                ['isbn' => $data['isbn']],
                [
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'category_id' => $catId,
                    'published_year' => $data['year'],
                    'total_copies' => $data['copies'],
                    'available_copies' => $data['copies'],
                    'description' => "A notable title in the {$data['category']} collection.",
                ]
            );
        });

        $firstMember = $members->first();
        $sampleBook = $books->first();

        if (!BorrowRecord::where('user_id', $firstMember->id)->where('book_id', $sampleBook->id)->exists()) {
            BorrowRecord::create([
                'user_id' => $firstMember->id,
                'book_id' => $sampleBook->id,
                'borrowed_at' => now()->subDays(20),
                'due_date' => now()->subDays(6),
                'status' => 'borrowed',
            ]);
            $sampleBook->decrement('available_copies');
        }

        $secondBook = $books->get(1);
        $secondMember = $members->get(1);

        if (!BorrowRecord::where('user_id', $secondMember->id)->where('book_id', $secondBook->id)->exists()) {
            BorrowRecord::create([
                'user_id' => $secondMember->id,
                'book_id' => $secondBook->id,
                'borrowed_at' => now()->subDays(5),
                'due_date' => now()->addDays(9),
                'status' => 'borrowed',
            ]);
            $secondBook->decrement('available_copies');
        }

        $thirdBook = $books->get(2);

        if (!BorrowRecord::where('user_id', $firstMember->id)->where('book_id', $thirdBook->id)->exists()) {
            BorrowRecord::create([
                'user_id' => $firstMember->id,
                'book_id' => $thirdBook->id,
                'borrowed_at' => now()->subDays(30),
                'due_date' => now()->subDays(16),
                'returned_at' => now()->subDays(18),
                'status' => 'returned',
            ]);
        }
    }
}
