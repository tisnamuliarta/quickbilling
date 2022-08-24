<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>{{ $type }}</title>
  @include('export.partials.style')
</head>
<body>
<!-- Define header and footer blocks before your content -->
<div class="header">
  <table class=" table-borderless table-sm">
  </table>
</div>

<div style="font-size: 10px!important" class="text-center footer">
  <span class="text-center">
    <strong>{{ strtoupper($company['company_name'])  }}</strong> <br>
    {{ $company['company_address'] }} <br>
    Phone {{ $company['company_phone'] }} | E-mail {{ $company['company_email'] }}
  </span>
  <span class="num-page">
      <script type="text/php">
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 11;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = $pdf->get_width() - 80;
            $y = $pdf->get_height() - 50;
            $pdf->page_text($x, $y, $text, $font, $size);
      </script>
  </span>
</div>

<div class="text-center" style="margin-top: -30px">
  <span style="font-size: 20px; font-weight: bold;">{{ $type }}</span>
</div>

<table class=" table-borderless table-sm" style="margin-bottom: 20px; width: 50%; margin-top: 50px">
  <tr>
    <td>{{ __('No Contract') }}</td>
    <td>:</td>
    <td style="font-weight: bold">
      {{ strtoupper($documents->reference_no) }}
    </td>
  </tr>
  <tr>
    <td>{{ __('No Document') }}</td>
    <td>:</td>
    <td style="font-weight: bold">
      {{ strtoupper($documents->transaction_no) }}
    </td>
  </tr>
  <tr>
    <td>{{ __('Bill To') }}</td>
    <td>:</td>
    <td style="font-weight: bold">{{ strtoupper($documents->contact_name) }}</td>
  </tr>
</table>

<span>Berikut merupakan hasil rekapan Invoice:</span>

<table class='table  table-sm' style="width: 100% !important; margin-bottom: 30px; margin-top: 10px;">
  <thead>
  <tr style="font-weight: 500">
    <td class="text-left">{{ __('NO')  }}</td>
    <td class="text-center">{{ __('ITEM NAME') }}</td>
    <td class="text-center">{{ __('SPECIFICATION') }}</td>
    <td class="text-center">{{ __('UOM') }}</td>
    <td class="text-right">{{ __('QTY') }}</td>
    <td class="text-right">{{ __('PRICE') }}</td>
    <td class="text-right">{{ __('AMOUNT') }}</td>
  </tr>
  </thead>

  <tbody>
  @foreach($documents->lineItems as $index => $row)
    <tr>
      <td>{{ ++$index }}</td>
      <td width="150px">{{ $row->narration }}</td>
      <td></td>
      <td>{{ $row->unit }}</td>
      <td class="text-right">{{ number_format($row->quantity, 0) }}</td>
      <td class="text-right">{{ number_format($row->amount, 2) }}</td>
      <td class="text-right">{{ number_format($row->sub_total, 2)  }}</td>
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
    <td class="text-right" colspan="3">{{ __('TOTAL') }}</td>
    <td class="text-right" style="font-size: 1rem"><b>{{ number_format($documents->main_account_amount, 2) }}</b></td>
  </tr>
  </tfoot>
</table>

@if($documents->transaction_type == 'IN')
  <table class=" table-borderless table-sm table-active" style="text-align: left; width: 40%; float: left;">
    <tr>
      <td colspan="3">
        <span>TRANSFER KE REKENING</span>
      </td>
    </tr>
    <tr>
      <td width="30">{{ __('Issue Bank') }}</td>
      <td width="5">:</td>
      <td width="65">{{ $company['company_bank_name'] }}</td>
    </tr>
    <tr>
      <td width="30">{{ __('Beneficiary') }}</td>
      <td width="5">:</td>
      <td width="65">{{ $company['company_bank_account_name'] }}</td>
    </tr>
    <tr>
      <td width="30">{{ __('Bank Account') }}</td>
      <td width="5">:</td>
      <td width="65">{{ $company['company_bank_account_number'] }}</td>
    </tr>
  </table>
@endif

<table class=" table-borderless table-sm" style="text-align: right; width: 31%; float: right;">
  <tr>
    <th>Morowali, {{ $document_date }}</th>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #222222 !important; height: 120px;">
    </td>
  </tr>
  <tr class="text-center">
    <td>{{ strtoupper($company['company_name'])  }}</td>
  </tr>
</table>

</body>
</html>
