<h2>My Purchase History</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Course</th>
        <th>Trainer</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Status</th>
        <th>Date</th>
        <th>Invoice</th> <!-- ✅ New -->
    </tr>

    @forelse($payments as $payment)
        <tr>
            <td>{{ $payment->course->title ?? 'N/A' }}</td>
            <td>{{ $payment->trainer->name ?? 'N/A' }}</td>
            <td>{{ $payment->amount }} {{ $payment->currency }}</td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
            <td>
                <span style="color: {{ $payment->status === 'success' ? 'green' : 'red' }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </td>
            <td>{{ $payment->created_at->format('d M Y') }}</td>

            <td>
                @if($payment->receipt_url)
                    <a href="{{ $payment->receipt_url }}" target="_blank">
                        View Invoice
                    </a>
                @else
                    N/A
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No purchase history found</td>
        </tr>
    @endforelse
</table>
