@php
    /** @var \Illuminate\Database\Eloquent\Model $actionable */
    $actionableType = get_class($actionable);
    $actionableId = $actionable->getKey();

    // Safe query in view: single record show page => 1 query only
    $activities = \App\Models\Activity::query()
        ->where('actionable_type', $actionableType)
        ->where('actionable_id', $actionableId)
        ->latest('activity_at')
        ->limit(50)
        ->get();
@endphp

<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Activities & Follow-up</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <div class="mb-6">
            @include('activities._form', ['actionable' => $actionable])
        </div>

        <div class="mt-6">
            @include('activities._list', ['activities' => $activities])
        </div>
    </div>
</div>
