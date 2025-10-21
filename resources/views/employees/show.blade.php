<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $employee->first_name }} {{ $employee->last_name }}
                @if($employee->discontinued_at)
                    <span class="ms-2 text-xs text-red-600 align-middle">Discontinued</span>
                @endif
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-800 rounded-full border border-gray-300 shadow-sm hover:bg-gray-100">Back</a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800">Edit</a>
                @if(!$employee->discontinued_at)
                    <form method="POST" action="{{ route('employees.discontinue', $employee) }}" onsubmit="return confirm('Mark this employee as discontinued?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-full shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">Discontinue</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('employees.reactivate', $employee) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-full shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">Reactivate</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div x-data="{ tab: 'overview' }" class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300">← Back to Dashboard</a>
                    </div>

                    @php
                        $currencyCode = $employee->currency ?? 'USD';
                        $monthlySalary = (float) ($employee->salary ?? 0);
                        $forecastMonths = [4, 6, 8, 12];

                        $from = request('from');
                        $to = request('to');

                        $paymentsQuery = \App\Models\EmployeePayment::where('employee_id', $employee->id);
                        if ($from) { $paymentsQuery->whereDate('paid_at', '>=', $from); }
                        if ($to) { $paymentsQuery->whereDate('paid_at', '<=', $to); }
                        $payments = $paymentsQuery->orderByDesc('paid_at')->get();

                        $totalPaid = \App\Models\EmployeePayment::where('employee_id', $employee->id)
                            ->whereNotNull('amount')
                            ->when($from, fn($q) => $q->whereDate('paid_at', '>=', $from))
                            ->when($to, fn($q) => $q->whereDate('paid_at', '<=', $to))
                            ->sum('amount');

                        $orgPaidByCurrency = \App\Models\EmployeePayment::whereNotNull('amount')
                            ->when($from, fn($q) => $q->whereDate('paid_at', '>=', $from))
                            ->when($to, fn($q) => $q->whereDate('paid_at', '<=', $to))
                            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(amount) as total')
                            ->groupBy('currency')
                            ->get();
                    @endphp

                    <div class="mt-6">
                        <div class="flex flex-wrap items-center gap-2 mb-4 bg-gray-100 rounded-xl p-1">
                            <button type="button" @click="tab='overview'" :class="tab==='overview' ? 'bg-black text-white shadow' : 'text-gray-700 hover:bg-white'" class="px-4 py-2 rounded-lg text-sm transition">Overview</button>
                            <button type="button" @click="tab='bank'" :class="tab==='bank' ? 'bg-black text-white shadow' : 'text-gray-700 hover:bg-white'" class="px-4 py-2 rounded-lg text-sm transition">Bank accounts</button>
                            <button type="button" @click="tab='access'" :class="tab==='access' ? 'bg-black text-white shadow' : 'text-gray-700 hover:bg-white'" class="px-4 py-2 rounded-lg text-sm transition">Shared access</button>
                            <button type="button" @click="tab='timeline'" :class="tab==='timeline' ? 'bg-black text-white shadow' : 'text-gray-700 hover:bg-white'" class="px-4 py-2 rounded-lg text-sm transition">Timeline</button>
                            <button type="button" @click="tab='org'" :class="tab==='org' ? 'bg-black text-white shadow' : 'text-gray-700 hover:bg-white'" class="px-4 py-2 rounded-lg text-sm transition">Org totals</button>
                        </div>

                        <div x-cloak x-show="tab==='overview'" x-transition.opacity class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold">Compensation Overview</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Summary of payments and projected cost based on current monthly salary.</p>
                            <form method="GET" class="mt-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                                <div>
                                    <x-input-label for="from" value="From" />
                                    <x-text-input id="from" type="date" name="from" class="mt-1 block w-full" value="{{ request('from') }}" />
                                </div>
                                <div>
                                    <x-input-label for="to" value="To" />
                                    <x-text-input id="to" type="date" name="to" class="mt-1 block w-full" value="{{ request('to') }}" />
                                </div>
                                <div class="sm:col-span-3 flex items-center gap-3">
                                    <x-primary-button class="bg-black hover:bg-gray-800">Apply</x-primary-button>
                                    @if(request('from') || request('to'))
                                        <a href="{{ route('employees.show', $employee) }}" class="text-gray-700 text-sm">Clear</a>
                                    @endif
                                </div>
                            </form>
                            @if(request('from') || request('to'))
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Filtering payments @if(request('from')) from {{ request('from') }} @endif @if(request('to')) to {{ request('to') }} @endif</div>
                            @endif
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <div class="text-sm text-gray-600">Total Paid</div>
                                <div class="mt-1 text-2xl font-semibold">{{ number_format($totalPaid, 2) }} {{ $currencyCode }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Monthly Salary</div>
                                <div class="mt-1 text-2xl font-semibold">{{ number_format($monthlySalary, 2) }} {{ $currencyCode }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Annual (12 mo)</div>
                                <div class="mt-1 text-2xl font-semibold">{{ number_format($monthlySalary * 12, 2) }} {{ $currencyCode }}</div>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach ($forecastMonths as $m)
                                    <div class="rounded-xl border border-gray-200 p-4">
                                        <div class="text-xs uppercase tracking-wide text-gray-600">{{ $m }} months</div>
                                        <div class="mt-1 text-lg font-semibold">{{ number_format($monthlySalary * $m, 2) }} {{ $currencyCode }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                            <div class="mt-6 flex items-center gap-4">
                                <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600">Edit</a>
                                <a href="{{ route('employees.index') }}" class="text-gray-600">Back</a>
                                @if($employee->discontinued_at)
                                    <span class="text-xs text-red-600">Discontinued at {{ $employee->discontinued_at->toDayDateTimeString() }}</span>
                                @endif
                            </div>
                        </div>
                    <!-- Organization totals across all employees -->
                    <div x-cloak x-show="tab==='org'" x-transition.opacity class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold">Total Paid to All Employees</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Cumulative payments across the organization (all time), by currency.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($orgPaidByCurrency as $row)
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $row->currency }}</div>
                                    <div class="text-xl font-semibold">{{ number_format($row->total, 2) }} {{ $row->currency }}</div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">No payments recorded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Accounts moved above Payment Timeline -->
            <div x-cloak x-show="tab==='bank'" x-transition.opacity class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Bank Accounts</h3>

                    <form method="POST" action="{{ route('employees.bank-accounts.store', $employee) }}" class="mb-6 space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="ba_title" value="Title" />
                            <x-text-input id="ba_title" type="text" name="title" class="mt-1 block w-full" placeholder="e.g. Primary USD account" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="ba_details" value="Details (Markdown)" />
                            <textarea id="ba_details" name="details_markdown" rows="4" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Account number, routing, bank name...&#10;&#10;You can use **bold**, lists, etc."></textarea>
                            <x-input-error :messages="$errors->get('details_markdown')" class="mt-2" />
                        </div>
                        <div>
                            <x-primary-button class="bg-black hover:bg-gray-800">Add account</x-primary-button>
                        </div>
                    </form>

                    <ul class="space-y-4">
                        @forelse($employee->bankAccounts as $account)
                            <li class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ $account->title }}</div>
                                        <div class="prose prose-sm mt-2">
                                            {!! \Illuminate\Support\Str::of($account->details_markdown)->markdown() !!}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-gray-700 text-sm" x-data @click="$dispatch('open-modal', 'edit-account-{{ $account->id }}')">Edit</button>
                                        <form method="POST" action="{{ route('employees.bank-accounts.destroy', [$employee, $account]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-sm" onclick="return confirm('Remove this account?')">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <x-modal name="edit-account-{{ $account->id }}">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4">Edit Bank Account</h4>
                                        <form method="POST" action="{{ route('employees.bank-accounts.update', [$employee, $account]) }}" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <x-input-label for="title_{{ $account->id }}" value="Title" />
                                                <x-text-input id="title_{{ $account->id }}" type="text" name="title" class="mt-1 block w-full" value="{{ $account->title }}" required />
                                            </div>
                                            <div>
                                                <x-input-label for="details_{{ $account->id }}" value="Details (Markdown)" />
                                                <textarea id="details_{{ $account->id }}" name="details_markdown" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ $account->details_markdown }}</textarea>
                                            </div>
                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" class="text-gray-700" x-data @click="$dispatch('close-modal', 'edit-account-{{ $account->id }}')">Cancel</button>
                                                <x-primary-button class="bg-black hover:bg-gray-800">Save changes</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            </li>
                        @empty
                            <li class="text-gray-500">No bank accounts added.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Shared Access section -->
            <div x-cloak x-show="tab==='access'" x-transition.opacity class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Shared Access</h3>

                    <form method="POST" action="{{ route('employees.accesses.store', $employee) }}" class="mb-6 space-y-3" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="acc_title" value="Title" />
                            <x-text-input id="acc_title" type="text" name="title" class="mt-1 block w-full" placeholder="e.g. GitHub, AWS, CRM" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="acc_note" value="Note (Markdown)" />
                            <textarea id="acc_note" name="note_markdown" rows="3" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Access details, user names, scopes... (optional)"></textarea>
                            <x-input-error :messages="$errors->get('note_markdown')" class="mt-2" />
                        </div>
                        <div x-data="{ isDragging:false }" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false" class="border-2 border-dashed rounded-lg p-4" :class="isDragging ? 'border-black bg-gray-50 dark:bg-gray-900' : 'border-gray-300 dark:border-gray-600'">
                            <x-input-label for="acc_file" value="Attachment (optional)" />
                            <input id="acc_file" name="attachment" type="file" class="mt-1 block w-full" />
                            <p class="text-xs text-gray-500 mt-1">Drag & drop or click to upload (max 10 MB).</p>
                        </div>
                        <div>
                            <x-primary-button class="bg-black hover:bg-gray-800">Add access</x-primary-button>
                        </div>
                    </form>

                    <ul class="space-y-4">
                        @forelse($employee->accesses as $access)
                            <li class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ $access->title }}</div>
                                        @if($access->note_markdown)
                                            <div class="prose prose-sm mt-2">{!! \Illuminate\Support\Str::of($access->note_markdown)->markdown() !!}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-gray-700 text-sm" x-data @click="$dispatch('open-modal', 'edit-access-{{ $access->id }}')">Edit</button>
                                        <form method="POST" action="{{ route('employees.accesses.destroy', [$employee, $access]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-sm" onclick="return confirm('Remove this access?')">Delete</button>
                                        </form>
                                    </div>
                                    @if($access->attachment_path)
                                        <div class="mt-2 text-sm">
                                            <a href="{{ asset('storage/'.$access->attachment_path) }}" class="text-indigo-600" target="_blank">{{ $access->attachment_name ?? 'Download attachment' }}</a>
                                        </div>
                                    @endif
                                </div>

                                <x-modal name="edit-access-{{ $access->id }}">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4">Edit Access</h4>
                                        <form method="POST" action="{{ route('employees.accesses.update', [$employee, $access]) }}" class="space-y-3" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <x-input-label for="acc_title_{{ $access->id }}" value="Title" />
                                                <x-text-input id="acc_title_{{ $access->id }}" type="text" name="title" class="mt-1 block w-full" value="{{ $access->title }}" required />
                                            </div>
                                            <div>
                                                <x-input-label for="acc_note_{{ $access->id }}" value="Note (Markdown)" />
                                                <textarea id="acc_note_{{ $access->id }}" name="note_markdown" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ $access->note_markdown }}</textarea>
                                            </div>
                                            <div>
                                                <x-input-label for="acc_file_{{ $access->id }}" value="Attachment (optional)" />
                                                <input id="acc_file_{{ $access->id }}" name="attachment" type="file" class="mt-1 block w-full" />
                                            </div>
                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" class="text-gray-700" x-data @click="$dispatch('close-modal', 'edit-access-{{ $access->id }}')">Cancel</button>
                                                <x-primary-button class="bg-black hover:bg-gray-800">Save changes</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            </li>
                        @empty
                            <li class="text-gray-500">No shared access recorded.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div x-cloak x-show="tab==='timeline'" x-transition.opacity class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Payment Timeline</h3>

                    <form method="POST" action="{{ route('employees.payments.store', $employee) }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div>
                            <x-input-label for="paid_at" value="Paid Date" />
                            <x-text-input id="paid_at" type="date" name="paid_at" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('paid_at')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="amount" value="Amount" />
                            <x-text-input id="amount" type="number" step="0.01" name="amount" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="currency" value="Currency" />
                            <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 rounded-md">
                                @php($currencies = ['USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 'BDT' => 'Bangladeshi Taka', 'INR' => 'Indian Rupee'])
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" @selected(old('currency', $employee->currency ?? 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>
                        <div class="md:col-span-4">
                            <x-input-label for="note" value="Note" />
                            <textarea id="note" name="note" rows="2" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Notes about this payment...">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>
                        <div>
                            <x-primary-button class="bg-black hover:bg-gray-800">Add to timeline</x-primary-button>
                        </div>
                    </form>

                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        <ul class="space-y-6">
                            @forelse($payments as $payment)
                                <li class="relative pl-12">
                                    <span class="absolute left-2 top-1.5 w-4 h-4 rounded-full bg-black"></span>
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($payment->paid_at)->toFormattedDateString() }}</div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="text-gray-700 text-sm" x-data @click="$dispatch('open-modal', 'edit-payment-{{ $payment->id }}')">Edit</button>
                                            <form method="POST" action="{{ route('employees.payments.destroy', [$employee, $payment]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 text-sm" onclick="return confirm('Remove this entry?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        @if($payment->amount)
                                            Amount: {{ $payment->amount }} {{ $payment->currency ?? $employee->currency ?? 'USD' }}
                                        @endif
                                    </div>
                                    @if($payment->note)
                                        <div class="mt-2 bg-gray-50 border border-gray-200 rounded-md p-3 text-sm">{{ $payment->note }}</div>
                                    @endif

                                    <x-modal name="edit-payment-{{ $payment->id }}">
                                        <div class="p-6">
                                            <h4 class="text-lg font-semibold mb-4">Edit Payment</h4>
                                            <form method="POST" action="{{ route('employees.payments.update', [$employee, $payment]) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <x-input-label for="paid_at_{{ $payment->id }}" value="Paid Date" />
                                                    <x-text-input id="paid_at_{{ $payment->id }}" type="date" name="paid_at" class="mt-1 block w-full" value="{{ $payment->paid_at }}" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="amount_{{ $payment->id }}" value="Amount" />
                                                    <x-text-input id="amount_{{ $payment->id }}" type="number" step="0.01" name="amount" class="mt-1 block w-full" value="{{ $payment->amount }}" />
                                                </div>
                                                <div>
                                                    <x-input-label for="currency_{{ $payment->id }}" value="Currency" />
                                                    <select id="currency_{{ $payment->id }}" name="currency" class="mt-1 block w-full border-gray-300 rounded-md">
                                                        @php($currencies = ['USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 'BDT' => 'Bangladeshi Taka', 'INR' => 'Indian Rupee'])
                                                        @foreach($currencies as $code => $label)
                                                            <option value="{{ $code }}" @selected(($payment->currency ?? $employee->currency ?? 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="md:col-span-4">
                                                    <x-input-label for="note_{{ $payment->id }}" value="Note" />
                                                    <textarea id="note_{{ $payment->id }}" name="note" rows="2" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Notes about this payment...">{{ $payment->note }}</textarea>
                                                </div>
                                                <div class="md:col-span-4 flex items-center justify-end gap-3 mt-2">
                                                    <button type="button" class="text-gray-700" x-data @click="$dispatch('close-modal', 'edit-payment-{{ $payment->id }}')">Cancel</button>
                                                    <x-primary-button class="bg-black hover:bg-gray-800">Save changes</x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>
                                </li>
                            @empty
                                <li class="pl-12 text-gray-500">No payments added yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


