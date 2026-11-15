<div class="row g-3">
    <div class="col-md-8">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label for="published_year" class="form-label">Published Year</label>
        <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            <option value="">Uncategorized</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="total_copies" class="form-label">Total Copies</label>
        <input type="number" min="1" class="form-control @error('total_copies') is-invalid @enderror" id="total_copies" name="total_copies" value="{{ old('total_copies', $book->total_copies ?? 1) }}" required>
        @isset($book)
            <div class="form-text">Currently {{ $book->available_copies }} of {{ $book->total_copies }} available. Adjusting the total will recalculate availability.</div>
        @endisset
    </div>
    <div class="col-12">
        <label for="cover_image" class="form-label">Cover Image URL <span class="text-muted">(optional)</span></label>
        <input type="url" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" value="{{ old('cover_image', $book->cover_image ?? '') }}" placeholder="https://...">
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $book->description ?? '') }}</textarea>
    </div>
</div>
