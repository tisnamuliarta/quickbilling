<tr>
  <th>Transaction No</th>
  <th>Vendor</th>
  <th>No Contract</th>
  <th>Transaction Date</th>
  <th>Due Date</th>
  <th>Status</th>
  <th class="text-right">Balance Due</th>
  <th class="text-right">Total</th>
</tr>

@php $total_balance_due = 0 @endphp
@php $total = 0 @endphp

@foreach($data as $item)
  @php $total_balance_due += $item->balance_due @endphp
  @php $total += $item->main_account_amount @endphp
  <tr>
    <td class="disable-wrap">{{ $item->transaction_no  }}</td>
    <td class="disable-wrap">{{ ($item->contact) ? $item->contact->name : ''  }}</td>
    <td class="disable-wrap">{{ $item->reference_no  }}</td>
    <td class="disable-wrap">{{ $item->transaction_date  }}</td>
    <td class="disable-wrap">{{ $item->due_date  }}</td>
    <td class="disable-wrap">{{ $item->status  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->balance_due, 2)  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->main_account_amount, 2)  }}</td>
  </tr>
@endforeach
<tr>
  <td colspan="6"><strong>Total</strong></td>
  <td class="text-right" style="border-top: 1px solid #222222;">
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total_balance_due, 2)  }}
    </strong>
  </td>
  <td class="text-right" style="border-top: 1px solid #222222;">
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total, 2)  }}
    </strong>
  </td>
</tr>
