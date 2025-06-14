<x-app-layout>
    <x-success-message />
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Terms & Conditions</h1>
        <a href="{{ route('terms.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($terms as $term)
                <tr>
                    <td class="border px-4 py-2">{{ $term->title }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('terms.edit', $term->id) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('terms.destroy', $term->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this?')" class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center py-4">No data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $terms->links() }}
    </div>
</x-app-layout>
