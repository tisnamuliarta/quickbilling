<table class='table  table-sm' style="width: 100%; margin-bottom: 30px">
  <thead>
  <tr>
    <th class="text-left">NO</th>
    <th class="text-center">DESCRIPTION</th>
    <th class="text-center">QTY</th>
    <th class="text-center">UNIT PRICE ({{ $documents->currency->symbol }})</th>
    <th class="text-center">DISCOUNT</th>
    <th class="text-center">TAXED</th>
    <th class="text-center">AMOUNT ({{ $documents->currency->symbol }})</th>
  </tr>
  </thead>

  <tbody>
  @foreach($documents->items as $index => $row)
    <tr>
      <td>{{ ++$index }}</td>
      <td>{{ $row->name . ' - '. $row->description }}</td>
      <td class="text-right">{{ number_format($row->quantity, 0) . ' '. $row->unit }}</td>
      <td class="text-right">{{ number_format($row->price, 2) }}</td>
      <td class="text-right">{{ number_format($row->discount_rate, 1) . '%' }}</td>
      <td class="text-center">X</td>
      <td class="text-right">{{ number_format($row->total, 2)  }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <td colspan="3" rowspan="5" style="border-left: none !important; border-bottom: none !important;">
      <table class=" table-borderless table-sm" style="margin: 30px 0">
        <tr>
          <td>AMOUNT IN WORD</td>
        </tr>
        <tr>
          <td>{{ $amount }}</td>
        </tr>
      </table>
    </td>
    <td class="text-right" colspan="3">{{ strtoupper('Sub Total') }}</td>
    <td class="text-right">{{ number_format($documents->sub_total, 2) }}</td>
  </tr>
  <tr>
    <td class="text-right" colspan="3">{{ strtoupper('Discount per Item') }}</td>
    <td class="text-right">{{ number_format($documents->discount_per_line, 2) }}</td>
  </tr>

  <tr>
    <td class="text-right" colspan="3">DISCOUNT {{ number_format($documents->discount_rate, 0) . ' %' }}</td>
    <td class="text-right">{{ number_format($documents->discount_amount, 2) }}</td>
  </tr>

  @foreach($documents->taxDetails as $tax)
    <tr>
      <td class="text-right" colspan="3">{{ $tax->name . ' '. number_format($tax->tax->rate) . ' %' }}</td>
      <td class="text-right">{{ number_format($tax->amount, 2) }}</td>
    </tr>
  @endforeach
  <tr class="table-active">
    <td class="text-right" colspan="3"><b>{{ strtoupper('Total') }}</b></td>
    <td class="text-right"><b>{{ number_format($documents->amount, 2) }}</b></td>
  </tr>
  </tfoot>
</table>

<table class=" table-borderless table-sm" style="text-align: right; width: 31%; float: right;">
  <tr>
    <th></th>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #222222 !important; height: 120px;">
    </td>
  </tr>
</table>
