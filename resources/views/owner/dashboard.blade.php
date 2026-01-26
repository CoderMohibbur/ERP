<x-app-layout>
    <x-success-message />

    @php
        $dueInvoices   = collect($dueInvoices ?? []);
        $renewalsDue   = collect($renewalsDue ?? []);
        $followUpsDue  = collect($followUpsDue ?? []);
        $stuckTasks    = collect($stuckTasks ?? []);
        $teamLoad      = collect($teamLoad ?? []);

        $dueTotal = $dueInvoices->sum(function ($inv) {
            return (float) ($inv->balance ?? ($inv->total ?? 0) - ($inv->paid_total ?? 0));
        });

        $renewalsCount = $renewalsDue->count();
        $followupsCount = $followUpsDue->count();
        $stuckCount = $stuckTasks->count();
    @endphp

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Owner Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Overview of due invoices, renewals, follow-ups, stuck tasks and team load.
        </p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Due Invoices</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $dueInvoices->count() }}</div>
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Total Due: <span class="font-semibold">{{ number_format((float) $dueTotal, 2) }}</span>
            </div>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Renewals Due (30d)</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $renewalsCount }}</div>
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Next 30 days</div>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Follow-ups Due</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $followupsCount }}</div>
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Today / overdue</div>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Stuck Tasks</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $stuckCount }}</div>
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Doing / Review 오래</div>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Team Load</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $teamLoad->count() }}</div>
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Hours this week</div>
        </div>
    </div>

    {{-- Due Invoices --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-white">Due Invoices (Top 20)</h2>
            @if (\Illuminate\Support\Facades\Route::has('invoices.index'))
                <a href="{{ route('invoices.index') }}"
                   class="text-sm font-semibold text-green-700 dark:text-green-400 hover:underline">
                    View all
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Invoice</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Due Date</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Balance</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($dueInvoices->take(20) as $invoice)
                        @php
                            $balance = (float) ($invoice->balance ?? (($invoice->total ?? 0) - ($invoice->paid_total ?? 0)));
                            $clientName = optional($invoice->client)->name
                                ?? optional($invoice->customer)->name
                                ?? ($invoice->client_name ?? '—');
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $invoice->invoice_no ?? ('#'.$invoice->id) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ $clientName }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($invoice->due_date)->format('Y-m-d') ?? $invoice->due_date ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right font-semibold">
                                {{ number_format($balance, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                @if (\Illuminate\Support\Facades\Route::has('invoices.show'))
                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        View
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No due invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Renewals Due --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 dark:text-white">Renewals Due (Next 30 Days)</h2>
            @if (\Illuminate\Support\Facades\Route::has('services.index'))
                <a href="{{ route('services.index', ['renewal_due' => 30]) }}"
                   class="text-sm font-semibold text-green-700 dark:text-green-400 hover:underline">
                    View all
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Service</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Next Renewal</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($renewalsDue->take(20) as $service)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $service->name ?? $service->title ?? ('#'.$service->id) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($service->client)->name ?? $service->client_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($service->next_renewal_at)->format('Y-m-d') ?? $service->next_renewal_at ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                @if (\Illuminate\Support\Facades\Route::has('services.show'))
                                    <a href="{{ route('services.show', $service->id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        View
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No renewals due in next 30 days.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Follow-ups Due --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Follow-ups Due (Today / Overdue)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Subject</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Due At</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Owner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($followUpsDue->take(20) as $activity)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ $activity->type ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $activity->subject ?? $activity->title ?? $activity->notes ?? ('#'.$activity->id) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($activity->follow_up_at)->format('Y-m-d H:i') ?? $activity->follow_up_at ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($activity->owner)->name ?? optional($activity->user)->name ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No follow-ups due.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stuck Tasks --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Stuck Tasks (Doing/Review 오래)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Task</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Project</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Assignee</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($stuckTasks->take(20) as $task)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $task->title ?? $task->name ?? ('#'.$task->id) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($task->project)->name ?? $task->project_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    {{ $task->status ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($task->assignee)->name ?? optional($task->user)->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($task->updated_at)->format('Y-m-d H:i') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No stuck tasks found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
