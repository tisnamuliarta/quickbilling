<tr>
  <th>Date</th>
  <th>Transaction Date</th>
  <th>Transaction Type</th>
  <th>Transaction No</th>
  <th>Item Name</th>
  <th>Qty</th>
  <th>Price</th>
  <th class="text-right">Inventory Value</th>
  <th class="text-right">Unit Cost</th>
  <th class="text-right">COGS</th>
</tr>

@foreach($report as $item)
  <tr>
    <td class="disable-wrap">{{ $item->created_at  }}</td>
    <td class="disable-wrap">{{ $item->transaction_date  }}</td>
    <td class="disable-wrap">{{ $item->transaction->type  }}</td>
    <td class="disable-wrap">{{ $item->transaction->transaction_no  }}</td>
    <td class="disable-wrap">{{ $item->item->name  }}</td>
    <td class="disable-wrap">{{ number_format($item->quantity)  }}</td>
    <td class="disable-wrap">{{ number_format($item->amount)  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->inventory_value, 2)  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->main_account_amount, 2)  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->cogs_value, 2)  }}</td>
  </tr>
@endforeach
