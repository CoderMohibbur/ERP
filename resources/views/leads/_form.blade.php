@php
    /** @var \App\Models\Lead|null $lead */
    $lead = $lead ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name"
               value="{{ old('name', $lead?->name) }}"
               required
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="phone" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Phone <span class="text-red-500">*</span></label>
        <input type="text" name="phone" id="phone"
               value="{{ old('phone', $lead?->phone) }}"
               required
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="email" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" name="email" id="email"
               value="{{ old('email', $lead?->email) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="source" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Source</label>
        <select name="source" id="source"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">—</option>
            @foreach(($sources ?? ['whatsapp','facebook','website','referral']) as $src)
                <option value="{{ $src }}" @selected(old('source', $lead?->source) === $src)>{{ ucfirst($src) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="status" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" required
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach(($statuses ?? ['new','contacted','qualified','unqualified']) as $s)
                <option value="{{ $s }}" @selected(old('status', $lead?->status ?? 'new') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="owner_id" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Owner <span class="text-red-500">*</span></label>
        <select name="owner_id" id="owner_id" required
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select owner</option>
            @foreach(($owners ?? []) as $u)
                <option value="{{ $u->id }}" @selected((string)old('owner_id', $lead?->owner_id) === (string)$u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="next_follow_up_at" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Next Follow-up</label>
        <input type="date" name="next_follow_up_at" id="next_follow_up_at"
               value="{{ old('next_follow_up_at', $lead?->next_follow_up_at ? \Illuminate\Support\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d') : null) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
</div>
