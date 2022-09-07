<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>{{ $report_type }}</title>
  <style>
    .styled-table {
      border-collapse: collapse;
      margin: 25px 0;
      font-size: 0.9em;
      font-family: sans-serif;
      min-width: 400px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
      background-color: #009879;
      color: #ffffff;
      text-align: left;
    }

    .styled-table th,
    .styled-table td {
      padding: 12px 15px;
    }

    .styled-table tbody tr {
      border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
      border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr.active-row {
      font-weight: bold;
      color: #009879;
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
  <span style="font-size: 20px; font-weight: bold; text-decoration: underline;">{{ $report_type }}</span>
</div>

<div class="theme--light">
  <div class="v-data-table v-data-table--dense theme--light">
    <div class="v-data-table__wrapper">
      <table class="table-borderless table-sm styled-table" style="margin-bottom: 20px; width: 100%; margin-top: 30px">
        <tbody>
        @include($report['view'], ['report' => $report['data']])
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
