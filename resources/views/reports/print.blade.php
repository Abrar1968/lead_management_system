<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .avoid-break {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-900 p-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8 border-b pb-4 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Monthly Report</h1>
            <p class="text-lg text-gray-600">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-indigo-700">WhatsApp CRM</h2>
            <p class="text-sm text-gray-500">Generated on {{ now()->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="mb-8 grid grid-cols-5 gap-4">
        <div class="rounded-lg border bg-gray-50 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 uppercase">Total Leads</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalLeads) }}</p>
        </div>
        <div class="rounded-lg border bg-gray-50 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 uppercase">Calls Made</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($totalCalls) }}</p>
        </div>
        <div class="rounded-lg border bg-gray-50 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 uppercase">Conversions</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($totalConversions) }}</p>
            <p class="text-[10px] text-gray-500">{{ $conversionRate }}% rate</p>
        </div>
        <div class="rounded-lg border bg-gray-50 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 uppercase">Deal Value</p>
            <p class="mt-1 text-2xl font-bold text-purple-600">৳{{ number_format($totalDealValue) }}</p>
        </div>
        <div class="rounded-lg border bg-gray-50 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 uppercase">Commission</p>
            <p class="mt-1 text-2xl font-bold text-orange-600">৳{{ number_format($totalCommission) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <!-- Source Breakdown -->
        <div class="avoid-break">
            <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-500 border-b pb-1">Lead Sources</h3>
            <table class="w-full text-sm">
                <tbody class="divide-y">
                    @forelse($sourceBreakdown as $source => $count)
                        <tr>
                            <td class="py-2 text-gray-700">{{ $source }}</td>
                            <td class="py-2 text-right font-medium">{{ $count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-2 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Service Breakdown -->
        <div class="avoid-break">
            <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-500 border-b pb-1">Services Interested
            </h3>
            <table class="w-full text-sm">
                <tbody class="divide-y">
                    @forelse($serviceBreakdown as $service => $count)
                        <tr>
                            <td class="py-2 text-gray-700">{{ $service }}</td>
                            <td class="py-2 text-right font-medium">{{ $count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-2 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Conversions List -->
    <div class="mb-8 avoid-break">
        <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-500 border-b pb-1">Conversions Detail</h3>
        <table class="min-w-full divide-y divide-gray-200 border text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left font-medium text-gray-500">Date</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500">Lead Name</th>
                    <th class="px-3 py-2 text-right font-medium text-gray-500">Deal Value</th>
                    <th class="px-3 py-2 text-right font-medium text-gray-500">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($conversions as $conversion)
                    <tr>
                        <td class="px-3 py-2 text-gray-600">{{ $conversion->conversion_date->format('d M') }}</td>
                        <td class="px-3 py-2 font-medium text-gray-900">{{ $conversion->lead->customer_name }}</td>
                        <td class="px-3 py-2 text-right text-gray-900">৳{{ number_format($conversion->deal_value) }}
                        </td>
                        <td class="px-3 py-2 text-right text-green-600">
                            ৳{{ number_format($conversion->commission_amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-center text-gray-500">No conversions recorded for this
                            month.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-right">Total:</td>
                    <td class="px-3 py-2 text-right">৳{{ number_format($totalDealValue) }}</td>
                    <td class="px-3 py-2 text-right text-green-700">৳{{ number_format($totalCommission) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="mt-12 text-center text-xs text-gray-400 no-print">
        <button onclick="window.print()"
            class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 font-bold">
            Print / Save as PDF
        </button>
        <p class="mt-2">Use browser print settings to save as PDF.</p>
    </div>
</body>

</html>
