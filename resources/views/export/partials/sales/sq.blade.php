<table class='table  table-sm' style="width: 100%; margin-bottom: 30px">
  <thead>
  <tr>
    <th class="text-left simsun">{{ __('NO') . ' 实收数量' }}</th>
    <th class="text-center">{{ __('ITEM NAME') }}</th>
    <th class="text-center">{{ __('SPECIFICATION') }}</th>
    <th class="text-center">{{ __('UOM') }}</th>
    <th class="text-right">{{ __('QTY') }}</th>
    <th class="text-right">{{ __('PRICE') }}</th>
    <th class="text-right">{{ __('AMOUNT') }}</th>
  </tr>
  </thead>

  <tbody>
  @foreach($documents->lineItems as $index => $row)
    <tr>
      <td>{{ ++$index }}</td>
      <td>{{ $row->name . ' - '. $row->narration }}</td>
      <td></td>
      <td >{{ $row->unit }}</td>
      <td class="text-right">{{ number_format($row->quantity, 0) }}</td>
      <td class="text-right">{{ number_format($row->price, 2) }}</td>
      <td class="text-right">{{ number_format($row->amount, 2)  }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <td colspan="3" rowspan="5" style="border-left: none !important; border-bottom: none !important;">
      <table class=" table-borderless table-sm" style="margin: 30px 0">
        <tr>
          <td>{{ __('Amount In Word')  }}</td>
        </tr>
        <tr>
          <td>{{ $amount }}</td>
        </tr>
      </table>
    </td>
    <td class="text-right" colspan="3">{{ strtoupper('Sub Total') }}</td>
    <td class="text-right">{{ number_format($documents->sub_total, 2) }}</td>
  </tr>

  @if($documents->discount_per_line > 0)
    <tr>
      <td class="text-right" colspan="3">{{ strtoupper('Discount per Item') }}</td>
      <td class="text-right">{{ number_format($documents->discount_per_line, 2) }}</td>
    </tr>
  @endif

  @if($documents->discount_rate > 0)
    <tr>
      <td class="text-right" colspan="3">DISCOUNT {{ number_format($documents->discount_rate, 0) . ' %' }}</td>
      <td class="text-right">{{ number_format($documents->discount_amount, 2) }}</td>
    </tr>
  @endif

  @foreach($documents->taxDetails as $tax)
    <tr>
      <td class="text-right" colspan="3">{{ $tax->name }}</td>
      <td class="text-right">{{ number_format($tax->amount, 2) }}</td>
    </tr>
  @endforeach
  <tr class="table-active">
    <td class="text-right" colspan="3"><b>{{ __('TOTAL') }}</b></td>
    <td class="text-right"><b>{{ number_format($documents->main_account_amount, 2) }}</b></td>
  </tr>
  </tfoot>
</table>

<table class=" table-borderless table-sm" style="text-align: left; width: 31%; float: left;">
  <tr>
    <td>{{ __('Issue Bank') }}</td>
    <td>:</td>
    <td>{{ $company['company_bank_name'] }}</td>
  </tr>
  <tr>
    <td>{{ __('Beneficiary') }}</td>
    <td>:</td>
    <td>{{ $company['company_bank_account_name'] }}</td>
  </tr>
  <tr>
    <td>{{ __('Bank Account') }}</td>
    <td>:</td>
    <td>{{ $company['company_bank_account_number'] }}</td>
  </tr>
</table>

<table class=" table-borderless table-sm" style="text-align: right; width: 31%; float: right;">
  <tr>
    <th>Morowali, {{ $document_date }}</th>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #222222 !important; height: 120px;">
    </td>
  </tr>
  <tr>
    <td>{{ strtoupper($company['company_name'])  }}</td>
  </tr>
</table>
