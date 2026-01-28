@php
    $selectedLeadId = old('lead_id', $deal->lead_id ?? null);
    $selectedClientId = old('client_id', $deal->client_id ?? null);

    // Determine default link type (Lead vs Client)
    $linkType = old('link_type');
    if (!$linkType) {
        $linkType = $selectedClientId ? 'client' : 'lead';
    }
@endphp

<div class="grid grid-cols-1 gap-4">

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title"
               value="{{ old('title', $deal->title ?? '') }}"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
               required>
        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Stage + Link Type --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Stage --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Stage <span class="text-red-500">*</span>
            </label>
            <select name="stage"
                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    required>
                @foreach(($stages ?? []) as $stage)
                    <option value="{{ $stage }}"
                        @selected(old('stage', $deal->stage ?? 'new') === $stage)>
                        {{ ucfirst($stage) }}
                    </option>
                @endforeach
            </select>
            @error('stage') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Link Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Attach deal to <span class="text-red-500">*</span>
            </label>

            <div class="mt-2 flex items-center gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="link_type" value="lead"
                           class="rounded border-gray-300"
                           @checked($linkType === 'lead')>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Lead</span>
                </label>

                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="link_type" value="client"
                           class="rounded border-gray-300"
                           @checked($linkType === 'client')>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Client</span>
                </label>
            </div>

            @error('link_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>

    {{-- Lead/Client selectors (toggle) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Lead --}}
        <div id="leadWrap">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Lead <span class="text-red-500">*</span>
            </label>
            <select name="lead_id" id="leadSelect"
                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <option value="">—</option>
                @foreach(($leads ?? []) as $lead)
                    <option value="{{ $lead->id }}" @selected((string)$selectedLeadId === (string)$lead->id)>
                        {{ $lead->name }}
                    </option>
                @endforeach
            </select>
            @error('lead_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Client --}}
        <div id="clientWrap">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Client <span class="text-red-500">*</span>
            </label>
            <select name="client_id" id="clientSelect"
                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <option value="">—</option>
                @foreach(($clients ?? []) as $client)
                    <option value="{{ $client->id }}" @selected((string)$selectedClientId === (string)$client->id)>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>

    {{-- Estimated Value + Probability --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Estimated Value
            </label>
            <input type="number" step="0.01" name="estimated_value"
                   value="{{ old('estimated_value', $deal->estimated_value ?? '') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('estimated_value') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Probability (0-100)
            </label>
            <input type="number" min="0" max="100" name="probability"
                   value="{{ old('probability', $deal->probability ?? '') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('probability') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Expected Close Date + Lost Reason --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Expected Close Date
            </label>
            <input type="date" name="expected_close_date"
                   value="{{ old('expected_close_date', optional($deal->expected_close_date ?? null)->format('Y-m-d')) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('expected_close_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Lost Reason (optional)
            </label>
            <input type="text" name="lost_reason"
                   value="{{ old('lost_reason', $deal->lost_reason ?? '') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('lost_reason') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

</div>

{{-- Toggle Script (inline, safe) --}}
<script>
    (function () {
        const leadWrap = document.getElementById('leadWrap');
        const clientWrap = document.getElementById('clientWrap');
        const leadSelect = document.getElementById('leadSelect');
        const clientSelect = document.getElementById('clientSelect');
        const radios = document.querySelectorAll('input[name="link_type"]');

        function apply() {
            const type = document.querySelector('input[name="link_type"]:checked')?.value || 'lead';

            if (type === 'lead') {
                leadWrap.style.display = '';
                clientWrap.style.display = 'none';
                if (clientSelect) clientSelect.value = '';
            } else {
                clientWrap.style.display = '';
                leadWrap.style.display = 'none';
                if (leadSelect) leadSelect.value = '';
            }
        }

        radios.forEach(r => r.addEventListener('change', apply));

        // Run once on load
        apply();
    })();
</script>
