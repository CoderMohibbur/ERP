<x-app-layout>
    <h1 class="text-xl font-bold mb-4">
        {{ isset($term) ? 'Edit Term' : 'Create New Term' }}
    </h1>

    <form action="{{ isset($term) ? route('terms.update', $term) : route('terms.store') }}" method="POST">
        @csrf
        @if(isset($term)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block font-medium">Title</label>
            <input name="title" value="{{ old('title', $term->title ?? '') }}"
                   class="input input-bordered w-full" required />
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="4"
                      class="input input-bordered w-full">{{ old('description', $term->description ?? '') }}</textarea>
        </div>

        <button class="btn btn-success">{{ isset($term) ? 'Update' : 'Create' }}</button>
    </form>
</x-app-layout>
