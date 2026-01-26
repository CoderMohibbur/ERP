@php
    $types = [
        'call' => 'Call',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'meeting' => 'Meeting',
        'note' => 'Note',
    ];

    $actionableType = $actionableType ?? (isset($actionable) ? get_class($actionable) : '');
    $actionableId = $actionableId ?? (isset($actionable) ? $actionable->getKey() : '');

    $nowLocal = now()->format('Y-m-d\TH:i');
@endphp

<x-validation-errors />

<form method="POST" action="{{ route('activities.store') }}" class="space-y-4">
    @csrf

    <input type="hidden" name="actionable_type" value="{{ $actionableType }}">
    <input type="hidden" name="actionable_id" value="{{ $actionableId }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
            <input type="text" name="subject" value="{{ old('subject') }}" required
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
            <select name="type" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                @foreach($types as $k => $label)
                    <option value="{{ $k }}" @selected(old('type') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
        <textarea name="body" rows="3"
                  class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">{{ old('body') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Activity Time</label>
            <input type="datetime-local" name="activity_at" value="{{ old('activity_at', $nowLocal) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Next Follow-up (optional)</label>
            <input type="datetime-local" name="next_follow_up_at" value="{{ old('next_follow_up_at') }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Save Activity
        </button>
    </div>
</form>
