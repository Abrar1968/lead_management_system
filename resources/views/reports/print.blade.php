<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - {{ $periodLabel ?? \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

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

<body class="bg-gray-50 text-gray-900 p-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-10 pb-6 border-b-2 border-gray-200 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white shadow-lg">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ ucfirst($period ?? 'Monthly') }} Report</h1>
                <p class="text-lg font-medium text-indigo-600">{{ $periodLabel ?? \Carbon\Carbon::parse($month)->format('F Y') }}</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Lead MS</h2>
            <p class="text-sm text-gray-500 mt-1">Generated on {{ now()->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="mb-10 grid grid-cols-5 gap-4">
        <div class="rounded-2xl bg-white border border-gray-200 p-5 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Leads</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalLeads) }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-gray-200 p-5 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Calls Made</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($totalCalls) }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-gray-200 p-5 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Conversions</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ number_format($totalConversions) }}</p>
            <p class="text-[10px] font-medium text-gray-500">{{ $conversionRate }}% rate</p>
        </div>
        <div class="rounded-2xl bg-white border border-gray-200 p-5 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Deal Value</p>
            <p class="mt-1 text-2xl font-bold text-purple-600">৳{{ number_format($totalDealValue) }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-gray-200 p-5 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Commission</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">৳{{ number_format($totalCommission) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-10">
        <!-- Source Breakdown -->
        <div class="avoid-break rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-3">
                <h3 class="text-sm font-bold uppercase tracking-wide text-white">Lead Sources</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @forelse($sourceBreakdown as $source => $count)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $source }}</td>
                            <td class="px-5 py-3 text-right">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-900">{{ $count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-5 py-6 text-center text-gray-500 font-medium">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Service Breakdown -->
        <div class="avoid-break rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3">
                <h3 class="text-sm font-bold uppercase tracking-wide text-white">Services Interested</h3>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @forelse($serviceBreakdown as $service => $count)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $service }}</td>
                            <td class="px-5 py-3 text-right">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-900">{{ $count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-5 py-6 text-center text-gray-500 font-medium">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Conversions List -->
    <div class="mb-10 avoid-break rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-5 py-3">
            <h3 class="text-sm font-bold uppercase tracking-wide text-white">Conversions Detail</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Lead Name</th>
                    <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Deal Value</th>
                    <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($conversions as $conversion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-medium text-gray-600">{{ $conversion->conversion_date->format('d M') }}</td>
                        <td class="px-5 py-4 font-bold text-gray-900">{{ $conversion->lead->client_name }}</td>
                        <td class="px-5 py-4 text-right font-bold text-gray-900">৳{{ number_format($conversion->deal_value) }}</td>
                        <td class="px-5 py-4 text-right font-bold text-emerald-600">৳{{ number_format($conversion->commission_amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-500 font-medium">No conversions recorded for this month.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gradient-to-r from-gray-100 to-gray-50 font-bold">
                <tr>
                    <td colspan="2" class="px-5 py-4 text-right text-gray-700 uppercase tracking-wider text-xs">Total:</td>
                    <td class="px-5 py-4 text-right text-lg text-gray-900">৳{{ number_format($totalDealValue) }}</td>
                    <td class="px-5 py-4 text-right text-lg text-emerald-600">৳{{ number_format($totalCommission) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="mt-12 text-center no-print">
        <button onclick="window.print()"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl font-bold transition-all">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print / Save as PDF
        </button>
        <p class="mt-3 text-xs text-gray-400">Use browser print settings to save as PDF.</p>
    </div>
</body>

</html>
