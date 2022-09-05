<tr>
  <th>Date</th>
  <th>Transaction Type</th>
  <th>Transaction No</th>
  <th>Customer/Vendor</th>
  <th>Memo</th>
  <th class="text-right">Amount</th>
</tr>

@foreach($report as $account)
  @php $transactions = $account->getTransactions($start_date, $end_date) @endphp
  @if($transactions['total'])
    <tr>
      <td colspan="7">{{ $account->name }}</td>
    </tr>
    @foreach($transactions['transactions'] as $item)
      <tr>
        <td>{{ $item->date }}</td>
        <td>{{ $item->type }}</td>
        <td>{{ $item->transaction_no }}</td>
        <td>{{ $item->contact }}</td>
        <td>{{ $item->narration }}</td>
        <td class="text-right">{{  auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->contribution, 2) }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="5">
        <strong>Total</strong>
      </td>
      <td class="text-right" style="border-top: 1px solid #222222;">
        <strong>
          {{  auth()->user()->entity->currency->currency_symbol . ' '. number_format($transactions['total'], 2) }}
        </strong>
      </td>
    </tr>
  @endif
@endforeach
