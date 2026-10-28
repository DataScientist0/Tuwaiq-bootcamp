@props(['categories', 'selected' => null])

<fieldset>
    <legend class="label">Category</legend>
    <div class="flex flex-wrap gap-2">
        @foreach ($categories as $category)
            <label>
                <input type="radio" name="category_id" value="{{ $category->id }}" class="peer sr-only" required
                    @checked((string) $selected === (string) $category->id)>
                <span
                    class="badge {{ $category->badgeClasses() }} cursor-pointer px-3 py-1 text-sm opacity-60 transition peer-checked:opacity-100 peer-checked:ring-2 peer-focus-visible:ring-2 hover:opacity-100">
                    {{ $category->name }}
                </span>
            </label>
        @endforeach
    </div>
    <p class="mt-1 text-xs text-white/50">Choose one category.</p>
</fieldset>
