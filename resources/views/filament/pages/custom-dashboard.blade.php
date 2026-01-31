<x-filament::page>

    {{-- Today’s Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-5 bg-white shadow rounded-lg text-center">
            <h3 class="text-gray-500 text-sm">Invoices Today</h3>
            <p class="text-3xl font-semibold">{{ $this->getTodayTotals()['invoice_count'] }}</p>
        </div>

        <div class="p-5 bg-white shadow rounded-lg text-center">
            <h3 class="text-gray-500 text-sm">Pending Invoices</h3>
            <p class="text-3xl font-semibold">{{ $this->getTodayTotals()['pending_count'] }}</p>
        </div>

        <div class="p-5 bg-white shadow rounded-lg text-center">
            <h3 class="text-gray-500 text-sm">Total Amount Today</h3>
            <p class="text-3xl font-semibold">AED {{ number_format($this->getTodayTotals()['total_amount_today'],2) }}</p>
        </div>
    </div>

    {{-- Steps & Quote --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="p-5 bg-white shadow rounded-lg">
            <h2 class="text-lg font-bold mb-3">Steps of Operation</h2>
            <ul class="list-disc list-inside text-gray-700 space-y-1">
                @foreach ($this->getSteps() as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>
        </div>

        <div class="p-5 bg-blue-50 border border-blue-200 shadow rounded-lg">
            <h2 class="text-lg font-bold mb-3">Quote of the Day</h2>
            <p class="text-gray-800 text-lg italic">"{{ $this->getQuoteOfDay() }}"</p>
        </div>
    </div>

    {{-- Pending Invoices Table --}}
    <div class="p-5 bg-white shadow rounded-lg">
        <h2 class="text-lg font-bold mb-4">Pending Invoices (Last 5 Days)</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Invoice No</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Customer</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Amount</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($this->getPendingInvoices() as $inv)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $inv->inv_no }}</td>
                            <td class="px-4 py-2">{{ $inv->customer->name ?? '-' }}</td>
                            <td class="px-4 py-2">AED {{ number_format($inv->inv_total,2) }}</td>
                            <td class="px-4 py-2">{{ $inv->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-filament::page>
