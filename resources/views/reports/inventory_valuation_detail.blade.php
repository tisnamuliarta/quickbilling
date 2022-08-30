<tr>
  <th>Date</th>
  <th>Item Name</th>
  <th>On Hand Qty</th>
  <th>Ordered Qty</th>
  <th>Committed Qty</th>
  <th>Available Qty</th>
  <th class="text-right">Item Cost</th>
</tr>

@foreach($inventoryValuation as $item)
  <tr>
    <td class="disable-wrap">{{ $item->updated_at  }}</td>
    <td class="disable-wrap">{{ $item->item->name  }}</td>
    <td class="disable-wrap">{{ number_format($item->on_hand_qty)  }}</td>
    <td class="disable-wrap">{{ number_format($item->ordered_qty)  }}</td>
    <td class="disable-wrap">{{ number_format($item->committed_qty)  }}</td>
    <td class="disable-wrap">{{ number_format($item->available_qty)  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->item_cost, 2)  }}</td>
  </tr>
@endforeach
