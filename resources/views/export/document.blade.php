<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>{{ $type }}</title>

  <style>
    @font-face {
      font-family: 'founder';
      src: url({{ storage_path('fonts/founder.ttf') }}) format("truetype");
      font-weight: 500;
    }

    * {
      font-family: 'founder';
    }

    .simsun {
      font-family: 'founder';
    }

    body {
      color: #000000;
      font-size: .75rem;
      font-weight: 400;
      line-height: 1.3;
    }

    .text-lowercase {
      text-transform: lowercase !important;
    }

    .text-uppercase {
      text-transform: uppercase !important;
    }

    .text-capitalize {
      text-transform: capitalize !important;
    }

    .text-left {
      text-align: left !important;
    }

    .text-right {
      text-align: right !important;
    }

    .text-center {
      text-align: center !important;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
    }

    .table {
      width: 100%;
      /*margin-bottom: 1rem;*/
      color: #212529;
    }

    .table th,
    .table td {
      padding: 0.2rem .4rem;
      vertical-align: top;
      border-top: 1px solid #000000;
      page-break-inside: initial;
    }

    .table thead th {
      vertical-align: center;
      border-bottom: 1px solid #000000;
    }

    .table tbody + tbody {
      border-top: 1px solid #000000;
    }

    .table-sm th,
    .table-sm td {
      padding: 0.2rem .4rem;
    }

    .table-bordered {
      border: 1px solid #000000;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 1px;
    }

    .table-borderless {
      width: 100%;
    }

    .table-borderless th,
    .table-borderless td,
    .table-borderless thead th,
    .table-borderless tbody + tbody {
      border: 0 !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
      color: #212529;
      background-color: rgba(0, 0, 0, 0.075);
    }

    .table-primary,
    .table-primary > th,
    .table-primary > td {
      background-color: #b8daff;
    }

    .table-primary th,
    .table-primary td,
    .table-primary thead th,
    .table-primary tbody + tbody {
      border-color: #7abaff;
    }

    .table-hover .table-primary:hover {
      background-color: #9fcdff;
    }

    .table-hover .table-primary:hover > td,
    .table-hover .table-primary:hover > th {
      background-color: #9fcdff;
    }

    .table-secondary,
    .table-secondary > th,
    .table-secondary > td {
      background-color: #d6d8db;
    }

    .table-secondary th,
    .table-secondary td,
    .table-secondary thead th,
    .table-secondary tbody + tbody {
      border-color: #b3b7bb;
    }

    .table-hover .table-secondary:hover {
      background-color: #c8cbcf;
    }

    .table-hover .table-secondary:hover > td,
    .table-hover .table-secondary:hover > th {
      background-color: #c8cbcf;
    }

    .table-success,
    .table-success > th,
    .table-success > td {
      background-color: #c3e6cb;
    }

    .table-success th,
    .table-success td,
    .table-success thead th,
    .table-success tbody + tbody {
      border-color: #8fd19e;
    }

    .table-hover .table-success:hover {
      background-color: #b1dfbb;
    }

    .table-hover .table-success:hover > td,
    .table-hover .table-success:hover > th {
      background-color: #b1dfbb;
    }

    .table-info,
    .table-info > th,
    .table-info > td {
      background-color: #bee5eb;
    }

    .table-info th,
    .table-info td,
    .table-info thead th,
    .table-info tbody + tbody {
      border-color: #86cfda;
    }

    .table-hover .table-info:hover {
      background-color: #abdde5;
    }

    .table-hover .table-info:hover > td,
    .table-hover .table-info:hover > th {
      background-color: #abdde5;
    }

    .table-warning,
    .table-warning > th,
    .table-warning > td {
      background-color: #ffeeba;
    }

    .table-warning th,
    .table-warning td,
    .table-warning thead th,
    .table-warning tbody + tbody {
      border-color: #ffdf7e;
    }

    .table-hover .table-warning:hover {
      background-color: #ffe8a1;
    }

    .table-hover .table-warning:hover > td,
    .table-hover .table-warning:hover > th {
      background-color: #ffe8a1;
    }

    .table-danger,
    .table-danger > th,
    .table-danger > td {
      background-color: #f5c6cb;
    }

    .table-danger th,
    .table-danger td,
    .table-danger thead th,
    .table-danger tbody + tbody {
      border-color: #ed969e;
    }

    .table-hover .table-danger:hover {
      background-color: #f1b0b7;
    }

    .table-hover .table-danger:hover > td,
    .table-hover .table-danger:hover > th {
      background-color: #f1b0b7;
    }

    .table-light,
    .table-light > th,
    .table-light > td {
      background-color: #fdfdfe;
    }

    .table-light th,
    .table-light td,
    .table-light thead th,
    .table-light tbody + tbody {
      border-color: #fbfcfc;
    }

    .table-hover .table-light:hover {
      background-color: #ececf6;
    }

    .table-hover .table-light:hover > td,
    .table-hover .table-light:hover > th {
      background-color: #ececf6;
    }

    .table-dark,
    .table-dark > th,
    .table-dark > td {
      background-color: #c6c8ca;
    }

    .table-dark th,
    .table-dark td,
    .table-dark thead th,
    .table-dark tbody + tbody {
      border-color: #95999c;
    }

    .table-hover .table-dark:hover {
      background-color: #b9bbbe;
    }

    .table-hover .table-dark:hover > td,
    .table-hover .table-dark:hover > th {
      background-color: #b9bbbe;
    }

    .table-active,
    .table-active > th,
    .table-active > td {
      background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
      background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover > td,
    .table-hover .table-active:hover > th {
      background-color: rgba(0, 0, 0, 0.075);
    }

    .table .thead-dark th {
      color: #fff;
      background-color: #343a40;
      border-color: #454d55;
    }

    .table .thead-light th {
      color: #495057;
      background-color: #e9ecef;
      border-color: #000000;
    }

    .table-dark {
      color: #fff;
      background-color: #343a40;
    }

    .table-dark th,
    .table-dark td,
    .table-dark thead th {
      border-color: #454d55;
    }

    .table-dark.table-bordered {
      border: 0;
    }

    .table-dark.table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(255, 255, 255, 0.05);
    }

    .table-dark.table-hover tbody tr:hover {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.075);
    }

    .clearfix::after {
      display: block;
      clear: both;
      content: "";
    }

    p {
      font-size: 14px;
    }


    @page {
      margin: 50px 20px 30px 20px;
    }

    body {
      margin: 50px 20px 30px 20px;
    }


    .page-number:before {
      /*counter-increment: page;*/
      /*counter-reset: page 0;*/
      /*content: "Page " counter(page);*/
    }

    /*header {*/
    /*  position: fixed;*/
    /*  left: 0px;*/
    /*  right: 0px;*/
    /*  height: 70px;*/
    /*  margin-bottom: 10px;*/
    /*  border-bottom: 3px solid #000000;*/
    /*}*/
    /*.header {*/
    /*  margin-bottom: 10px;*/
    /*  border-bottom: 3px solid #000000;*/
    /*}*/

    /*footer {*/
    /*  position: fixed;*/
    /*  bottom: 5px;*/
    /*  left: 0px;*/
    /*  right: 0px;*/
    /*  height: 50px;*/
    /*}*/


    .page-break {
      page-break-after: always;
    }

    .page-break1 {
      page-break-after: always;
    }

    .page-break:last-child {
      page-break-after: avoid;
    }

    .header {
      position: absolute;
      top: -80px;
      left: 20px;
      right: 20px;
      height: 30px;
      /*margin-bottom: 10px;*/
      /*border-bottom: 2px solid #2e2e2e;*/
    }

    .footer {
      position: absolute;
      bottom: 5px;
      left: 20px;
      right: 20px;
      height: 50px;
    }

    .disable-wrap {
      white-space: nowrap !important;
    }
  </style>
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
