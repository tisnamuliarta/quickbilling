@php $total = 0; $item_name = ''; $balance_due = 0 @endphp

<tr>
  <th>Item</th>
  <th>Transaction Date</th>
  <th>Transaction Type</th>
  <th>Transaction No</th>
  <th>Notes</th>
  <th>Customer</th>
  <th class="text-right">Qty</th>
  <th class="text-right">Price</th>
  <th class="text-right">Amount</th>
  <th class="text-right">Balance Due</th>
</tr>
@foreach($report as $index => $item)
  <tr>
    <td colspan="10">{{ $item['item']  }}</td>
  </tr>
  @php $total_in = 0; $balance_due_in = 0 @endphp
  @foreach($item['list'] as $value)
    @php
      $total_in += $value->sub_total;
      $balance_due_in += $value->sub_total;
      $total += $value->sub_total;
      $balance_due += $value->transaction->balance_due;
    @endphp
    <tr>
      <td class="disable-wrap"></td>
      <td class="disable-wrap">{{ $value->transaction->date  }}</td>
      <td class="disable-wrap">{{ $value->transaction->type  }}</td>
      <td class="disable-wrap">{{ $value->transaction->transaction_no  }}</td>
      <td class="disable-wrap">{{ $value->transaction->narration  }}</td>
      <td class="disable-wrap">{{ $value->transaction->contact->name  }}</td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->quantity, 2)  }}
      </td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->amount, 2)  }}
      </td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->sub_total, 2)  }}
      </td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->transaction->balance_due, 2)  }}
      </td>
    </tr>
  @endforeach
  <tr>
    <td colspan="8"><strong></strong></td>
    <td class="text-right" style="border-top: 1px solid #222222;">
      <strong>
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total_in, 2)  }}
      </strong>
    </td>
    <td class="text-right" style="border-top: 1px solid #222222;">
      <strong>
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($balance_due_in, 2)  }}
      </strong>
    </td>
  </tr>
@endforeach
<tr>
  <td colspan="8"><strong>Total</strong></td>
  <td class="text-right" style="border-top: 1px solid #222222;">
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total, 2)  }}
    </strong>
  </td>
  <td class="text-right" style="border-top: 1px solid #222222;">
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($balance_due, 2)  }}
    </strong>
  </td>
</tr>
