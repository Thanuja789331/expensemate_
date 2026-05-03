@extends('layouts.app')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-gray-500 text-sm">Total Income</p>
        <p class="text-2xl font-bold text-green-600">
            ${{ number_format($totalIncome, 2) }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <p class="text-gray-500 text-sm">Total Expense</p>
        <p class="text-2xl font-bold text-red-600">
            ${{ number_format($totalExpense, 2) }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm">Balance</p>
        <p class="text-2xl font-bold text-blue-600">
            ${{ number_format($balance, 2) }}
        </p>
    </div>

</div>

{{-- Charts --}}
<div class="grid grid-cols-2 gap-6 mb-8">

    {{-- Pie Chart --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Expense Share by Category</h3>
        <canvas id="pieChart"></canvas>
    </div>

    {{-- Bar Chart --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Monthly Income vs Expense</h3>
        <canvas id="barChart"></canvas>
    </div>

</div>

{{-- Add New Record Button --}}
<div class="text-center">
    <a href="{{ route('expenses.create') }}"
       class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 text-lg">
        + Add New Record
    </a>
</div>

{{-- Chart Scripts --}}
<script>
    // ── Pie Chart Data ──────────────────────────
    const pieLabels = @json($categoryData->map(fn($d) => $d->category->name ?? 'Unknown'));
    const pieData   = @json($categoryData->map(fn($d) => $d->total));

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: [
                    '#16a34a','#dc2626','#2563eb','#d97706',
                    '#7c3aed','#0891b2','#be185d','#65a30d',
                    '#ea580c','#6366f1'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // ── Bar Chart Data ──────────────────────────
    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun',
                        'Jul','Aug','Sep','Oct','Nov','Dec'];

    const monthlyRaw = @json($monthlyData);

    // Build labels and datasets
    const labels   = [];
    const incomes  = [];
    const expenses = [];

    // Group by year-month
    const grouped = {};
    monthlyRaw.forEach(item => {
        const key = item.year + '-' + String(item.month).padStart(2, '0');
        if (!grouped[key]) {
            grouped[key] = { income: 0, expense: 0, month: item.month };
        }
        if (item.type === 'income')  grouped[key].income  = item.total;
        if (item.type === 'expense') grouped[key].expense = item.total;
    });

    Object.keys(grouped).sort().forEach(key => {
        labels.push(monthNames[grouped[key].month - 1]);
        incomes.push(grouped[key].income);
        expenses.push(grouped[key].expense);
    });

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomes,
                    backgroundColor: '#16a34a'
                },
                {
                    label: 'Expense',
                    data: expenses,
                    backgroundColor: '#dc2626'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
