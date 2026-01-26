    @php
    /** @var \App\Models\Deal|null $deal */
    $deal = $deal ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="title" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $deal?->title) }}"
               required
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="stage" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Stage <span class="text-red-500">*</span></label>
        <select name="stage" id="stage" required
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach(($stages ?? ['new','contacted','quoted','negotiating','won','lost']) as $s)
                <option value="{{ $s }}" @selected(old('stage', $deal?->stage ?? 'new') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Note: lead_id অথবা client_id — অন্তত ১টা দিন</div>
    </div>

    <div>
        <label for="lead_id" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Lead</label>
        <select name="lead_id" id="lead_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">—</option>
            @foreach(($leads ?? []) as $l)
                <option value="{{ $l->id }}" @selected((string)old('lead_id', $deal?->lead_id) === (string)$l->id)>{{ $l->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="client_id" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
        <select name="client_id" id="client_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">—</option>
            @foreach(($clients ?? []) as $c)
                <option value="{{ $c->id }}" @selected((string)old('client_id', $deal?->client_id) === (string)$c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="value_estimated" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Value</label>
        <input type="number" step="0.01" min="0" name="value_estimated" id="value_estimated"
               value="{{ old('value_estimated', $deal?->value_estimated) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="probability" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Probability (0-100)</label>
        <input type="number" min="0" max="100" name="probability" id="probability"
               value="{{ old('probability', $deal?->probability) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="expected_close_date" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Expected Close Date</label>
        <input type="date" name="expected_close_date" id="expected_close_date"
               value="{{ old('expected_close_date', $deal?->expected_close_date ? \Illuminate\Support\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : null) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="lost_reason" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Lost Reason (optional)</label>
        <input type="text" name="lost_reason" id="lost_reason"
               value="{{ old('lost_reason', $deal?->lost_reason) }}"
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
</div>
