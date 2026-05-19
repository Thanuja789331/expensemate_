<div style="overflow-x:hidden; width:100%;">

    {{-- ── FILTERS ── --}}
    <div style="background:white; border-radius:12px; padding:16px;
                border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                margin-bottom:16px; box-sizing:border-box;">

        <p style="font-size:12px; font-weight:700; color:#64748b; margin:0 0 12px;
                   text-transform:uppercase; letter-spacing:0.05em;">🔍 Filter Data</p>

        {{-- Filters grid --}}
        <div class="filter-grid">

            {{-- Type --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:600;
                              color:#64748b; margin-bottom:4px; text-transform:uppercase;">
                    Type
                </label>
                <select wire:model.live="filterType"
                        style="width:100%; padding:8px 10px; border:1px solid #e5e7eb;
                               border-radius:8px; font-size:13px; outline:none;
                               background:white; color:#1e293b; box-sizing:border-box;">
                    <option value="all">All Types</option>
                    <option value="income">Income Only</option>
                    <option value="expense">Expense Only</option>
                </select>
            </div>

            {{-- Month --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:600;
                              color:#64748b; margin-bottom:4px; text-transform:uppercase;">
                    Month
                </label>
                <select wire:model.live="filterMonth"
                        style="width:100%; padding:8px 10px; border:1px solid #e5e7eb;
                               border-radius:8px; font-size:13px; outline:none;
                               background:white; color:#1e293b; box-sizing:border-box;">
                    <option value="">All Months</option>
                    @foreach ($months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Year --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:600;
                              color:#64748b; margin-bottom:4px; text-transform:uppercase;">
                    Year
                </label>
                <select wire:model.live="filterYear"
                        style="width:100%; padding:8px 10px; border:1px solid #e5e7eb;
                               border-radius:8px; font-size:13px; outline:none;
                               background:white; color:#1e293b; box-sizing:border-box;">
                    <option value="">All Years</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <div style="display:flex; align-items:flex-end; gap:8px;">
                <button wire:click="resetFilters"
                        style="width:100%; padding:8px 16px; background:#1B6B4A;
                               color:white; border:none; border-radius:8px; font-size:13px;
                               font-weight:500; cursor:pointer; box-sizing:border-box;"
                        onmouseover="this.style.background='#155a3c'"
                        onmouseout="this.style.background='#1B6B4A'">
                    ↺ Reset
                </button>
                <span wire:loading style="font-size:18px; color:#1B6B4A;">⏳</span>
            </div>

        </div>
    </div>

    {{-- ── SUMMARY CARDS ── --}}
    <div class="sc-cards" style="margin-bottom:16px;">

        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid #1B6B4A;">
            <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                       font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                Total Income
            </p>
            <p style="font-size:22px; font-weight:700; color:#1B6B4A; margin:0;">
                {{ currency_symbol() }}{{ number_format($totalIncome, 2) }}
            </p>
        </div>

        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid #ef4444;">
            <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                       font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                Total Expense
            </p>
            <p style="font-size:22px; font-weight:700; color:#ef4444; margin:0;">
                {{ currency_symbol() }}{{ number_format($totalExpense, 2) }}
            </p>
        </div>

        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid {{ $balance >= 0 ? '#1B6B4A' : '#ef4444' }};">
            <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                       font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                Balance
            </p>
            <p style="font-size:22px; font-weight:700; margin:0;
                       color:{{ $balance >= 0 ? '#1B6B4A' : '#ef4444' }};">
                {{ $balance >= 0 ? '+' : '-' }}{{ currency_symbol() }}{{ number_format(abs($balance), 2) }}
            </p>
        </div>

    </div>

    {{-- ── CHARTS ── --}}
    <div class="sc-charts" style="margin-bottom:16px;">

        {{-- Pie --}}
        <div style="background:white; border-radius:12px; padding:20px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin:0 0 16px;">
                🥧 Expense by Category
            </h3>
            @if($categoryData->count() > 0)
                <div style="position:relative; width:100%; height:260px;">
                    <canvas id="pieChart"></canvas>
                </div>
            @else
                <div style="height:260px; display:flex; align-items:center;
                            justify-content:center; flex-direction:column; gap:8px;">
                    <p style="font-size:13px; color:#94a3b8; margin:0;">
                        No expense data for this period
                    </p>
                </div>
            @endif
        </div>

        {{-- Bar --}}
        <div style="background:white; border-radius:12px; padding:20px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin:0 0 16px;">
                📊 Monthly Trend (Last 6 Months)
            </h3>
            <div style="position:relative; width:100%; height:260px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ── CATEGORY TABLE ── --}}
    <div style="background:white; border-radius:12px; padding:20px;
                border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                margin-bottom:16px; overflow-x:auto;">

        <h3 style="font-size:14px; font-weight:700; color:#1e293b; margin:0 0 16px;">
            📋 Category Breakdown
        </h3>

        @if($categoryData->count() > 0)
        <table style="width:100%; border-collapse:collapse; font-size:13px; min-width:400px;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                    <th style="padding:10px 14px; text-align:left; font-size:11px;
                               font-weight:600; color:#9ca3af; text-transform:uppercase;">
                        Category
                    </th>
                    <th style="padding:10px 14px; text-align:center; font-size:11px;
                               font-weight:600; color:#9ca3af; text-transform:uppercase;">
                        Txn
                    </th>
                    <th style="padding:10px 14px; text-align:right; font-size:11px;
                               font-weight:600; color:#9ca3af; text-transform:uppercase;">
                        Amount
                    </th>
                    <th style="padding:10px 14px; text-align:right; font-size:11px;
                               font-weight:600; color:#9ca3af; text-transform:uppercase;">
                        Share
                    </th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = $categoryData->sum('amount'); @endphp
                @foreach ($categoryData as $cat)
                <tr style="border-bottom:1px solid #f9fafb;"
                    onmouseover="this.style.background='#fafafa'"
                    onmouseout="this.style.background='white'">
                    <td style="padding:12px 14px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:8px; height:8px; border-radius:50%;
                                        background:#1B6B4A; flex-shrink:0;"></div>
                            <span style="font-weight:500; color:#1e293b;">
                                {{ $cat['name'] }}
                            </span>
                        </div>
                    </td>
                    <td style="padding:12px 14px; text-align:center; color:#64748b;">
                        {{ $cat['count'] }}
                    </td>
                    <td style="padding:12px 14px; text-align:right;
                               font-weight:600; color:#ef4444;">
                        {{ currency_symbol() }}{{ number_format($cat['amount'], 2) }}
                    </td>
                    <td style="padding:12px 14px; text-align:right;">
                        @php $share = $grandTotal > 0
                            ? round(($cat['amount'] / $grandTotal) * 100, 1)
                            : 0; @endphp
                        <span style="font-size:12px; color:#64748b;">{{ $share }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc; border-top:2px solid #e5e7eb;">
                    <td style="padding:12px 14px; font-weight:700; color:#1e293b;">Total</td>
                    <td style="padding:12px 14px; text-align:center; color:#64748b;">
                        {{ $categoryData->sum('count') }}
                    </td>
                    <td style="padding:12px 14px; text-align:right; font-weight:700; color:#ef4444;">
                        {{ currency_symbol() }}{{ number_format($grandTotal, 2) }}
                    </td>
                    <td style="padding:12px 14px; text-align:right; color:#64748b;">100%</td>
                </tr>
            </tfoot>
        </table>
        @else
        <div style="padding:32px; text-align:center; color:#94a3b8;">
            <p style="font-size:14px; font-weight:500; margin:0 0 4px;">No data for this period</p>
            <p style="font-size:12px; margin:0;">Try changing the filters above</p>
        </div>
        @endif
    </div>

    {{-- CTA --}}
    <div style="text-align:center; margin-bottom:8px;">
        <a href="{{ route('expenses.create') }}"
           style="display:inline-block; padding:12px 32px; background:#1B6B4A;
                  color:white; border-radius:10px; font-size:14px; font-weight:600;
                  text-decoration:none;"
           onmouseover="this.style.background='#155a3c'"
           onmouseout="this.style.background='#1B6B4A'">
            + Add New Record
        </a>
    </div>

    {{-- Responsive CSS --}}
    <style>
        .filter-grid { display:grid; grid-template-columns:1fr; gap:10px; }
        .sc-cards    { display:grid; grid-template-columns:1fr; gap:12px; }
        .sc-charts   { display:grid; grid-template-columns:1fr; gap:16px; }

        @media (min-width:600px) {
            .filter-grid { grid-template-columns:1fr 1fr; }
            .sc-cards    { grid-template-columns:repeat(3,1fr); }
        }

        @media (min-width:1024px) {
            .filter-grid { grid-template-columns:1fr 1fr 1fr auto; }
            .sc-charts   { grid-template-columns:1fr 1fr; }
        }
    </style>

    {{-- Charts --}}
    <script>
        document.addEventListener('livewire:navigated', renderCharts);
        document.addEventListener('livewire:updated', renderCharts);
        document.addEventListener('DOMContentLoaded', renderCharts);

        function renderCharts() {
            const pieLabels  = @json($categoryData->pluck('name'));
            const pieAmounts = @json($categoryData->pluck('amount'));
            const barLabels  = @json($monthlyTrend->pluck('label'));
            const barIncome  = @json($monthlyTrend->pluck('income'));
            const barExpense = @json($monthlyTrend->pluck('expense'));

            const pieColors = [
                '#1B6B4A','#22c55e','#16a34a','#4ade80',
                '#86efac','#bbf7d0','#064e3b','#065f46',
                '#047857','#059669'
            ];

            ['pieChart','barChart'].forEach(id => {
                const c = Chart.getChart(id);
                if (c) c.destroy();
            });

            const pieCtx = document.getElementById('pieChart');
            if (pieCtx && pieLabels.length > 0) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieAmounts,
                            backgroundColor: pieColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11 }, padding: 10 }
                            }
                        }
                    }
                });
            }

            const barCtx = document.getElementById('barChart');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: barLabels,
                        datasets: [
                            {
                                label: 'Income',
                                data: barIncome,
                                backgroundColor: 'rgba(27,107,74,0.85)',
                                borderRadius: 6
                            },
                            {
                                label: 'Expense',
                                data: barExpense,
                                backgroundColor: 'rgba(239,68,68,0.85)',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11 }, padding: 10 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { size: 11 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }
        }
    </script>

</div>
