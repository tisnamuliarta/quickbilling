<tr>
  <th>Item Code</th>
  <th>Item Name</th>
  <th>Item Type</th>
  <th>Item Category</th>
  <th>Unit</th>
  <th>Commission</th>
  <th>Issue Method</th>
  <th>Material Type</th>
  <th>Valuation Method</th>
</tr>

@foreach($customer_account as $item)
  <tr>
    <td class="disable-wrap">{{ $item->code  }}</td>
    <td class="disable-wrap">{{ $item->name  }}</td>
    <td class="disable-wrap">{{ $item->group_name  }}</td>
    <td class="disable-wrap">{{ ($item->category) ? $item->category->name : '' }}</td>
    <td class="disable-wrap">{{ $item->unit  }}</td>
    <td class="text-right disable-wrap">{{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($item->commision_rate, 2)  }}</td>
    <td class="disable-wrap">{{ $item->issue_method  }}</td>
    <td class="disable-wrap">{{ $item->material_type  }}</td>
    <td class="disable-wrap">{{ $item->valuation_method  }}</td>
  </tr>
@endforeach
