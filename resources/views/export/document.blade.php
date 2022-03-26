<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Billing Export</title>
  <link rel="stylesheet" href="{{ asset('css/report_style.css') }}">
  <style>
    .header {
      position: absolute;
      top: -80px;
      left: 0;
      right: 0;
      height: 70px;
      margin-bottom: 10px;
      border-bottom: 3px solid #000000;
    }

    .footer {
      position: absolute;
      bottom: 5px;
      left: 0;
      right: 0;
      height: 50px;
    }
  </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<div class="header">
  <img width="160px" src="{{ public_path("files/logo/") . $company->company_logo }}" alt="">
</div>

<div style="font-size: 10px!important" class="text-center footer">
  <div style="border-top: 2px solid #9e9e9e; margin-bottom: 10px;"></div>
  <span class="num-page">
      <script type="text/php">
        if (isset($pdf)) {
          if($PAGE_NUM != 1) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 7;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = $pdf->get_width() - 70;
            $y = $pdf->get_height() - 50;
            $pdf->page_text($x, $y, $text, $font, $size);
          }
        }
      </script>
  </span>
  <p style="margin-top: 10px;">{{ $documents->document_number }}</p>
</div>

<h2 class="text-uppercase text-center">
  port service accounts
</h2>
<span>CUSTOMER</span>
<table class=" table-borderless table-sm" style="width: 60%; margin-bottom: 30px">
  <tbody>
  <tr>
    <td>NAME</td>
    <td>: {{ $documents->contact_name  }}</td>
  </tr>
  <tr>
    <td>ADDRESS</td>
    <td>: {{ $documents->contact_address  }}</td>
  </tr>
  </tbody>
</table>

<table class='table table-bordered table-sm' style="width: 100%; margin-bottom: 30px">
  <thead>
  <tr>
    <th class="text-center">NO</th>
    <th class="text-center">DESCRIPTION</th>
    <th class="text-center">QTY</th>
    <th class="text-center">UNIT PRICE ({{ $company->company_currency_symbol }})</th>
    <th class="text-center">DISCOUNT</th>
    <th class="text-center">TAXED</th>
    <th class="text-center">AMOUNT ({{ $company->company_currency_symbol }})</th>
  </tr>
  </thead>

  <tbody>
  @foreach($documents->items as $index => $row)
    <tr>
      <td>{{ ++$index }}</td>
      <td>{{ $row->name . ' - '. $row->description }}</td>
      <td>{{ $row->quantity . ' '. $row->unit }}</td>
      <td class="text-right">{{ number_format($row->discount_rate, 1) . '%' }}</td>
      <td>X</td>
      <td>{{ number_format($row->total, 2)  }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <td colspan="4"></td>
    <td colspan="2"><b>Sub Total</b></td>
    <td class="text-right"><b>{{ number_format($document->sub_total, 2) }}</b></td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td colspan="2"><b>Discount per Item</b></td>
    <td class="text-right"><b>{{ number_format($document->discount_per_line, 2) }}</b></td>
  </tr>
  @foreach($documents->tax_details as $tax)
    <tr>
      <td colspan="4"></td>
      <td colspan="2"><b>{{ $tax->name . ' '. number_format($tax->tax->rate) }}</b></td>
      <td class="text-right"><b>{{ number_format($tax->amount, 2) }}</b></td>
    </tr>
  @endforeach
  <tr class="table-active">
    <td colspan="4"></td>
    <td colspan="2"><b>Total</b></td>
    <td class="text-right"><b>{{ number_format($document->amount, 2) }}</b></td>
  </tr>
  </tfoot>
</table>

</body>
</html>
