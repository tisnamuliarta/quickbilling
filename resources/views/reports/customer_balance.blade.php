@php $total = 0 @endphp

<tr>
  <th>Customer</th>
  <th>Transaction Date</th>
  <th>Transaction Type</th>
  <th>Transaction No</th>
  <th>Notes</th>
  <th class="text-right">Amount</th>
</tr>
@foreach($report as $item)
  @php $total += $item['total'] @endphp
  <tr>
    <td>{{ $item['customer']  }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="text-right">
    </td>
  </tr>
  @foreach($item['transactions'] as $value)
    <tr>
      <td></td>
      <td class="disable-wrap">{{ $value->date  }}</td>
      <td class="disable-wrap">{{ $value->type  }}</td>
      <td class="disable-wrap">{{ $value->transaction_no  }}</td>
      <td class="disable-wrap">{{ $value->narration  }}</td>
      <td
        class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->contribution, 2)  }}</td>
    </tr>
  @endforeach
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="text-right" style="border-top: 1px solid #222222;">
      <strong>
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item['total'], 2)  }}
      </strong>
    </td>
  </tr>
@endforeach
<tr>
  <td><strong>Total</strong></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td class="text-right" style="border-top: 1px solid #222222;">
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total, 2)  }}
    </strong>
  </td>
</tr>
