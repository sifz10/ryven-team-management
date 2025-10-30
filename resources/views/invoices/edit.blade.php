<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Invoice') }} - {{ $invoice->invoice_number }}
            </h2>
            <a href="{{ route('invoices.show', $invoice) }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Invoice
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" x-data="invoiceForm()">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <!-- Invoice Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice Number</label>
                                <input type="text" name="invoice_number" 
                                    value="{{ old('invoice_number', $invoice->invoice_number) }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                @error('invoice_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice Date</label>
                                <input type="date" name="invoice_date" x-model="invoiceDate" 
                                    value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                @error('invoice_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
                                <input type="date" name="due_date" x-model="dueDate"
                                    value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                            <select name="currency" x-model="currency"
                                class="w-full md:w-1/3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                required>
                                <option value="USD" {{ $invoice->currency === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ $invoice->currency === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ $invoice->currency === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="CAD" {{ $invoice->currency === 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                <option value="AUD" {{ $invoice->currency === 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recipient Information -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Paid To</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Enter the person or business that Ryven Global LLC is paying</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recipient Name *</label>
                                    <input type="text" name="client_name" 
                                        placeholder="Person or Business Name" 
                                        value="{{ old('client_name', $invoice->client_name) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                        required>
                                    @error('client_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" name="client_email" 
                                        value="{{ old('client_email', $invoice->client_email) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('client_email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                    <input type="text" name="client_phone" 
                                        value="{{ old('client_phone', $invoice->client_phone) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error('client_phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                    <textarea name="client_address" rows="3"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('client_address', $invoice->client_address) }}</textarea>
                                    @error('client_address')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Invoice Items</h3>
                                <button type="button" @click="addItem()" 
                                    class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Item
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="flex gap-4 items-start bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                        <div class="flex-1">
                                            <input type="text" :name="'items[' + index + '][description]'" 
                                                x-model="item.description"
                                                placeholder="Description"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                                required>
                                        </div>
                                        <div class="w-24">
                                            <input type="number" :name="'items[' + index + '][quantity]'" 
                                                x-model="item.quantity"
                                                @input="calculateItemAmount(index)"
                                                placeholder="Qty"
                                                step="0.01" min="0"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                                required>
                                        </div>
                                        <div class="w-32">
                                            <input type="number" :name="'items[' + index + '][rate]'" 
                                                x-model="item.rate"
                                                @input="calculateItemAmount(index)"
                                                placeholder="Rate"
                                                step="0.01" min="0"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                                required>
                                        </div>
                                        <div class="w-32">
                                            <input type="text" :value="formatCurrency(item.amount)" 
                                                readonly
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 text-sm" 
                                                placeholder="Amount">
                                        </div>
                                        <button type="button" @click="removeItem(index)" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-end">
                                <div class="w-full md:w-1/2 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Subtotal:</span>
                                        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="formatCurrency(subtotal)"></span>
                                    </div>

                                    <div class="flex justify-between items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Tax:</span>
                                            <input type="number" name="tax_percentage" x-model="taxPercentage"
                                                @input="calculateTotals()"
                                                step="0.01" min="0" max="100"
                                                class="w-20 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                placeholder="0">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">%</span>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="formatCurrency(taxAmount)"></span>
                                    </div>

                                    <div class="flex justify-between items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Discount:</span>
                                            <input type="number" name="discount_percentage" x-model="discountPercentage"
                                                @input="calculateTotals()"
                                                step="0.01" min="0" max="100"
                                                class="w-20 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                placeholder="0">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">%</span>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="formatCurrency(discountAmount)"></span>
                                    </div>

                                    <div class="flex justify-between items-center pt-4 border-t border-gray-300 dark:border-gray-600">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Total:</span>
                                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400" x-text="formatCurrency(total)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for calculations -->
                        <input type="hidden" name="subtotal" x-model="subtotal">
                        <input type="hidden" name="tax_amount" x-model="taxAmount">
                        <input type="hidden" name="discount_amount" x-model="discountAmount">
                        <input type="hidden" name="total" x-model="total">

                        <!-- Additional Notes -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                <textarea name="notes" rows="4"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Additional notes or instructions...">{{ old('notes', $invoice->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Terms & Conditions</label>
                                <textarea name="terms" rows="8"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs"
                                    placeholder="Payment terms and conditions...">{{ old('terms', $invoice->terms) }}</textarea>
                                @error('terms')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status"
                                class="w-full md:w-1/3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                required>
                                <option value="draft" {{ old('status', $invoice->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', $invoice->status) === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ old('status', $invoice->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status', $invoice->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('invoices.show', $invoice) }}" 
                                class="inline-flex items-center px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                                Update Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function invoiceForm() {
            return {
                invoiceDate: '{{ old("invoice_date", $invoice->invoice_date->format("Y-m-d")) }}',
                dueDate: '{{ old("due_date", $invoice->due_date->format("Y-m-d")) }}',
                currency: '{{ old("currency", $invoice->currency) }}',
                items: @json(old('items', $invoice->items)),
                taxPercentage: {{ old('tax_percentage', $invoice->tax_percentage) }},
                discountPercentage: {{ old('discount_percentage', $invoice->discount_percentage) }},
                subtotal: 0,
                taxAmount: 0,
                discountAmount: 0,
                total: 0,

                init() {
                    this.calculateTotals();
                },

                addItem() {
                    this.items.push({ description: '', quantity: 1, rate: 0, amount: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    }
                },

                calculateItemAmount(index) {
                    const item = this.items[index];
                    item.amount = parseFloat(item.quantity || 0) * parseFloat(item.rate || 0);
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.subtotal = this.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.quantity || 0) * parseFloat(item.rate || 0));
                    }, 0);

                    this.taxAmount = this.subtotal * (parseFloat(this.taxPercentage || 0) / 100);
                    this.discountAmount = this.subtotal * (parseFloat(this.discountPercentage || 0) / 100);
                    this.total = this.subtotal + this.taxAmount - this.discountAmount;
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: this.currency
                    }).format(amount || 0);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

