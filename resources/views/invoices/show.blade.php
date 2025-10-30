<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoice') }} - {{ $invoice->invoice_number }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('invoices.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                <a href="{{ route('invoices.edit', $invoice) }}" 
                    class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                
                @if($invoice->client_email)
                    <form action="{{ route('invoices.send-email', $invoice) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                            onclick="return confirm('Send invoice to {{ $invoice->client_email }}?')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 text-white rounded-full shadow hover:bg-green-700 dark:hover:bg-green-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Send Email
                        </button>
                    </form>
                @else
                    <button type="button" 
                        disabled
                        title="Add recipient email address to enable sending"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-full shadow cursor-not-allowed opacity-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email
                    </button>
                @endif

                <a href="{{ route('invoices.preview', $invoice) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview PDF
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}" 
                    class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-8">
                    <!-- Header with Logo -->
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <img src="{{ asset('black-logo.png') }}" alt="Ryven Global LLC" class="h-16 mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Ryven Global LLC</h1>
                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p>+19299889564</p>
                                <p>hello@ryven.co</p>
                                <p>30 N Gould St Ste R</p>
                                <p>Sheridan, WY 82801</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-4xl font-bold text-black dark:text-white mb-2">INVOICE</h2>
                            <div class="text-sm space-y-1">
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Invoice #:</span> 
                                    <span class="text-gray-900 dark:text-gray-100">{{ $invoice->invoice_number }}</span>
                                </p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Date:</span> 
                                    <span class="text-gray-900 dark:text-gray-100">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                                </p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Due Date:</span> 
                                    <span class="text-gray-900 dark:text-gray-100">{{ $invoice->due_date->format('M d, Y') }}</span>
                                </p>
                                <div class="mt-2">
                                    @if($invoice->status === 'paid')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            PAID
                                        </span>
                                    @elseif($invoice->status === 'sent')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            SENT
                                        </span>
                                    @elseif($invoice->status === 'cancelled')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            CANCELLED
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            DRAFT
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paid To -->
                    <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Paid To</h3>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $invoice->client_name }}</p>
                        @if($invoice->client_email)
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client_email }}</p>
                        @endif
                        @if($invoice->client_phone)
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client_phone }}</p>
                        @endif
                        @if($invoice->client_address)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 whitespace-pre-line">{{ $invoice->client_address }}</p>
                        @endif
                    </div>

                    <!-- Items Table -->
                    <div class="mb-8 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Rate
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $item['description'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">
                                            {{ $invoice->currency }} {{ number_format($item['rate'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-semibold">
                                            {{ $invoice->currency }} {{ number_format($item['quantity'] * $item['rate'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="flex justify-end mb-8">
                        <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}
                                </span>
                            </div>

                            @if($invoice->tax_percentage > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Tax ({{ number_format($invoice->tax_percentage, 2) }}%):
                                    </span>
                                    <span class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}
                                    </span>
                                </div>
                            @endif

                            @if($invoice->discount_percentage > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Discount ({{ number_format($invoice->discount_percentage, 2) }}%):
                                    </span>
                                    <span class="text-base font-semibold text-red-600 dark:text-red-400">
                                        -{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-4 bg-gray-100 dark:bg-gray-900 px-4 rounded-lg border-2 border-black dark:border-gray-600">
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Total:</span>
                                <span class="text-2xl font-bold text-black dark:text-white">
                                    {{ $invoice->currency }} {{ number_format($invoice->total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes and Terms -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if($invoice->notes)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes:</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $invoice->notes }}</p>
                            </div>
                        @endif

                        @if($invoice->terms)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Terms & Conditions:</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $invoice->terms }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Thank you for your business!
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                            Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons at Bottom -->
            <div class="mt-6 flex justify-center gap-4">
                <button onclick="window.print()" 
                    class="inline-flex items-center px-6 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Invoice
                </button>

                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-2.5 bg-red-600 dark:bg-red-500 text-white rounded-full shadow hover:bg-red-700 dark:hover:bg-red-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Invoice
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .bg-white.dark\:bg-gray-800, .bg-white.dark\:bg-gray-800 * {
                visibility: visible;
            }
            .bg-white.dark\:bg-gray-800 {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            header, .flex.justify-center, .bg-blue-600, .bg-yellow-500, .bg-purple-600, .bg-green-600 {
                display: none !important;
            }
        }
    </style>
    @endpush
</x-app-layout>

