<div wire:poll.30000ms>

    {{-- Section Title --}}
    <div style="margin-bottom:16px;">
        <h2 style="font-size:16px; font-weight:700; color:#1e293b; margin:0 0 2px;">
            📊 Your Financial Overview
        </h2>
        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Auto-refreshes every 30 seconds
        </p>
    </div>

    {{-- ── ROW 1: Main Stats ── --}}
    <div class="stats-row1" style="display:grid; gap:12px; margin-bottom:12px;">

        {{-- Total Income --}}
        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid #1B6B4A;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                               font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                        Total Income
                    </p>
                    <p style="font-size:20px; font-weight:700; color:#1B6B4A; margin:0;">
                        ${{ number_format($totalIncome, 2) }}
                    </p>
                </div>
                <div style="width:36px; height:36px; border-radius:8px;
                            background:#f0fdf4; display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <svg style="width:18px; height:18px; color:#1B6B4A;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Expense --}}
        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid #ef4444;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                               font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                        Total Expense
                    </p>
                    <p style="font-size:20px; font-weight:700; color:#ef4444; margin:0;">
                        ${{ number_format($totalExpense, 2) }}
                    </p>
                </div>
                <div style="width:36px; height:36px; border-radius:8px;
                            background:#fef2f2; display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <svg style="width:18px; height:18px; color:#ef4444;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 12H4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Balance --}}
        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid {{ $balance >= 0 ? '#1B6B4A' : '#ef4444' }};">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                               font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                        Balance
                    </p>
                    <p style="font-size:20px; font-weight:700; margin:0;
                               color:{{ $balance >= 0 ? '#1B6B4A' : '#ef4444' }};">
                        {{ $balance >= 0 ? '+' : '-' }}${{ number_format(abs($balance), 2) }}
                    </p>
                </div>
                <div style="width:36px; height:36px; border-radius:8px;
                            background:{{ $balance >= 0 ? '#f0fdf4' : '#fef2f2' }};
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:18px; height:18px; color:{{ $balance >= 0 ? '#1B6B4A' : '#ef4444' }};"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);
                    border-left:4px solid #8b5cf6;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; color:#64748b; margin:0 0 4px;
                               font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                        Transactions
                    </p>
                    <p style="font-size:20px; font-weight:700; color:#8b5cf6; margin:0;">
                        {{ number_format($totalTransactions) }}
                    </p>
                </div>
                <div style="width:36px; height:36px; border-radius:8px;
                            background:#f5f3ff; display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <svg style="width:18px; height:18px; color:#8b5cf6;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ── ROW 2: This Month + Savings ── --}}
    <div class="stats-row2" style="display:grid; gap:12px; margin-bottom:16px;">

        {{-- This Month Income --}}
        <div style="background:linear-gradient(135deg, #1B6B4A, #22c55e);
                    border-radius:12px; padding:16px; color:white;
                    box-shadow:0 4px 12px rgba(27,107,74,0.25);">
            <p style="font-size:11px; margin:0 0 4px; opacity:0.85;
                       font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                This Month Income
            </p>
            <p style="font-size:20px; font-weight:700; margin:0;">
                ${{ number_format($monthIncome, 2) }}
            </p>
            <p style="font-size:11px; margin:4px 0 0; opacity:0.75;">
                {{ now()->format('F Y') }}
            </p>
        </div>

        {{-- This Month Expense --}}
        <div style="background:linear-gradient(135deg, #ef4444, #f97316);
                    border-radius:12px; padding:16px; color:white;
                    box-shadow:0 4px 12px rgba(239,68,68,0.25);">
            <p style="font-size:11px; margin:0 0 4px; opacity:0.85;
                       font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                This Month Expense
            </p>
            <p style="font-size:20px; font-weight:700; margin:0;">
                ${{ number_format($monthExpense, 2) }}
            </p>
            <p style="font-size:11px; margin:4px 0 0; opacity:0.75;">
                {{ now()->format('F Y') }}
            </p>
        </div>

        {{-- Savings Rate --}}
        <div class="stats-savings"
             style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <div style="display:flex; justify-content:space-between;
                        align-items:center; margin-bottom:8px;">
                <p style="font-size:11px; color:#64748b; margin:0;
                           font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                    Savings Rate (All Time)
                </p>
                <span style="font-size:16px; font-weight:700;
                             color:{{ $savingsRate >= 0 ? '#1B6B4A' : '#ef4444' }};">
                    {{ $savingsRate }}%
                </span>
            </div>
            <div style="background:#f1f5f9; border-radius:999px; height:8px; overflow:hidden;">
                @php $barWidth = max(0, min(100, $savingsRate)); @endphp
                <div style="height:100%; border-radius:999px; transition:width 0.5s;
                            width:{{ $barWidth }}%;
                            background:{{ $savingsRate >= 50 ? '#1B6B4A' : ($savingsRate >= 20 ? '#f59e0b' : '#ef4444') }};">
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:5px;">
                <span style="font-size:11px; color:#94a3b8;">0%</span>
                <span style="font-size:11px; color:#94a3b8;">
                    @if($savingsRate >= 50) 🎉 Great savings!
                    @elseif($savingsRate >= 20) 👍 Good progress
                    @elseif($savingsRate > 0) ⚡ Keep saving
                    @else ⚠️ Overspending
                    @endif
                </span>
                <span style="font-size:11px; color:#94a3b8;">100%</span>
            </div>
        </div>

    </div>

    {{-- Responsive grid via CSS --}}
    <style>
        /* Mobile: 1 column */
        .stats-row1 { grid-template-columns: 1fr; }
        .stats-row2 { grid-template-columns: 1fr; }
        .stats-savings { grid-column: span 1; }

        /* Tablet: 2 columns */
        @media (min-width: 600px) {
            .stats-row1 { grid-template-columns: 1fr 1fr; }
            .stats-row2 { grid-template-columns: 1fr 1fr; }
            .stats-savings { grid-column: span 2; }
        }

        /* Desktop: 4 columns row1, 1fr 1fr 2fr row2 */
        @media (min-width: 1024px) {
            .stats-row1 { grid-template-columns: repeat(4, 1fr); }
            .stats-row2 { grid-template-columns: 1fr 1fr 2fr; }
            .stats-savings { grid-column: span 1; }
        }
    </style>

</div>
