@extends('layouts.member')

@section('content')
@php
    // Ensure collections are initialized
    $months = $months ?? collect();
    $years = $years ?? collect();
    $summary = $summary ?? [];
    $selectedYear = $selectedYear ?? date('Y');
@endphp

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Monthly Savings Summary</h1>

        @if($years->isNotEmpty())
        <form action="{{ route('member.savings.monthly-summary') }}" method="GET" class="flex items-center space-x-4">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
                <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                        onchange="this.form.submit()" style="border: 1px solid #ccc; padding: 8px; font-size: 16px; border-radius: 5px;">
                    @foreach($years as $year)
                        <option value="{{ $year->year }}" {{ $selectedYear == $year->year ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Savings Type
                        </th>
                        @foreach($months as $month)
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $month->name }}
                            </th>
                        @endforeach
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @if(!empty($summary) && is_array($summary))
                    @forelse($summary as $typeId => $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data['name'] ?? 'N/A' }}
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if(isset($data['months'][$month->id]) && $data['months'][$month->id] > 0)
                                        ₦{{ number_format($data['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($data['total'] ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($months instanceof \Countable ? $months->count() : 0) + 2 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No savings records found for {{ $selectedYear }}.
                            </td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="{{ ($months instanceof \Countable ? $months->count() : 0) + 2 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No data available.
                        </td>
                    </tr>
                @endif

                <!-- Total Row -->
                @if(!empty($summary) && is_array($summary))
                    <tr class="bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <strong>Total All Savings</strong>
                        </td>

                        @foreach($months as $month)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                @php
                                    $monthTotal = 0;
                                    foreach($summary as $typeData) {
                                        $monthTotal += $typeData['months'][$month->id] ?? 0;
                                    }
                                @endphp

                                @if($monthTotal > 0)
                                    ₦{{ number_format($monthTotal, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                            @php
                                $grandTotal = 0;
                                foreach($summary as $typeData) {
                                    $grandTotal += $typeData['total'] ?? 0;
                                }
                            @endphp
                            ₦{{ number_format($grandTotal, 2) }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    @if(!empty($summary) && is_array($summary))
    <div class="mt-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Savings Visualization</h2>
            </div>
            <div class="p-6">
                <canvas id="savingsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    @endif
</div>

@if(!empty($summary) && is_array($summary))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('savingsChart').getContext('2d');

    // Prepare data for the chart
    const months = @json($months->pluck('name'));
    const savingTypes = @json(collect($summary)->pluck('name'));

    const datasets = [];
    const colors = [
        'rgba(75, 192, 192, 0.6)',
        'rgba(153, 102, 255, 0.6)',
        'rgba(255, 159, 64, 0.6)',
        'rgba(255, 99, 132, 0.6)',
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 206, 86, 0.6)',
    ];

    @foreach($summary as $typeId => $data)
        @php
            $colorIndex = $loop->index % 6; // 6 is the number of colors we have
        @endphp
        datasets.push({
            label: '{{ $data['name'] ?? 'N/A' }}',
            data: @json(collect($data['months'] ?? [])->values()),
            backgroundColor: colors[{{ $colorIndex }}],
            borderColor: colors[{{ $colorIndex }}].replace('0.6', '1'),
            borderWidth: 1
        });
    @endforeach

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₦' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₦' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection
