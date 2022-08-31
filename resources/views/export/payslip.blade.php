<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>{{ $type }}</title>
  @include('export.partials.style')

  <style>
    @page {
      margin: 1px 20px 20px 20px !important;
    }

    body {
      margin: 1px 20px 20px 20px !important;
    }
    .table-sm th,
    .table-sm td {
      padding: 0 .4rem;
    }

    .table th,
    .table td {
      padding: 0 .4rem;
      vertical-align: top;
      border-top: 1px solid #000000;
      page-break-inside: initial;
    }

  </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->

<div class="container">
  <div class="row">
    <div class="col-sm-6">
      <table class='table  table-sm' style="width: 100% !important;">
        <tbody>
        @foreach($lineItems as  $lineItem)
          <tr>
            <td style="border-top: none !important;">
              <table class='table  table-sm' style="width: 100% !important;">
                <tbody>
                <tr>
                  <td colspan="6" style="border-top: none !important;">
                    <div class="text-center" style=" margin-top: 5px">
                      <span style="font-size: 20px; font-weight: bold;">{{ $type }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="text-center" colspan="6" style="border-top: none !important;">
                    <div class="text-center">
                      {{ $period }}
                    </div>
                  </td>
                </tr>
                @foreach($lineItem['header'] as $index => $row)
                  <tr>
                    <td width="120px">{{ $index }}</td>
                    <td>:</td>
                    <td class="text-left">{{ $row }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                @endforeach
                @php $total_allowance = 0; $total_deduction = 0; @endphp
                <tr>
                  <th colspan="3">{{ strtoupper(__('Allowance'))  }}</th>
                  <th colspan="3">{{ strtoupper(__('Deduction'))  }}</th>
                </tr>
                <tr>
                  <td colspan="3" style="border-top: none !important;">
                    <table class='table  table-sm' style="width: 100% !important;">
                      <tbody>
                      @foreach($lineItem['allowance'] as $index => $row)
                        <tr>
                          <td width="115px">{{ $index }}</td>
                          <td>:</td>
                          <td class="text-right">
                            {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($row, 2) }}
                          </td>
                        </tr>
                        @php $total_allowance += $row; @endphp
                      @endforeach
                      </tbody>
                    </table>
                  </td>
                  <td colspan="3" style="border-top: none !important;">
                    <table class='table  table-sm' style="width: 100% !important;">
                      <tbody>
                      @foreach($lineItem['deduction'] as $index => $row)
                        <tr>
                          <td width="115px">{{ $index }}</td>
                          <td>:</td>
                          <td class="text-right">
                            {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($row, 2) }}
                          </td>
                        </tr>
                        @php $total_deduction += $row; @endphp
                      @endforeach
                      </tbody>
                    </table>
                  </td>
                </tr>

                <tr>
                  <td width="120px">Total (A)</td>
                  <td>:</td>
                  <td class="text-right">
                    <strong>
                      {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($total_allowance, 2) }}
                    </strong>
                  </td>
                  <td width="120px">Total (B)</td>
                  <td>:</td>
                  <td class="text-right">
                    <strong>
                      {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($total_deduction, 2) }}
                    </strong>
                  </td>
                </tr>


                <tr class="table-active">
                  <td colspan="5">{{ strtoupper(__('Total Pay')) }} (A-B)</td>
                  <td class="text-right">
                    <strong style="font-size: 16px">
                      {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($lineItem[__('Total Pay')], 2) }}
                    </strong>
                  </td>
                </tr>

                <tr>
                  <td colspan="6" style="border-bottom: 1px dashed #000000; padding-bottom: 15px">
                    <div style="font-size: 9px!important" class="text-center">
                      <strong>{{ strtoupper($company['company_name'])  }}</strong> <br>
                      {{ $company['company_address'] }}
                      Phone {{ $company['company_phone'] }} | E-mail {{ $company['company_email'] }}
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>


</body>
</html>
