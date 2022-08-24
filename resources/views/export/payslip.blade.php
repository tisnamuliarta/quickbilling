<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>{{ $type }}</title>
  @include('export.partials.style')

  <style>
    @page {
      margin: 2px 20px 20px 20px !important;
    }

    body {
      margin: 2px 20px 20px 20px !important;
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
                    <div class="text-center" style=" margin-top: 20px">
                      <span style="font-size: 20px; font-weight: bold;">{{ $type }}</span>
                    </div>
                  </td>
                </tr>
                @foreach($lineItem['header'] as $index => $row)
                  <tr>
                    <td>{{ $index }}</td>
                    <td>:</td>
                    <td class="text-right">{{ $row }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                @endforeach

                <tr>
                  <th colspan="3">{{ __('Allowance')  }}</th>
                  <th colspan="3">{{ __('Deduction')  }}</th>
                </tr>

                @foreach($lineItem['allowance'] as $index => $row)
                  <tr>
                    <td>{{ $index }}</td>
                    <td>:</td>
                    <td class="text-right">
                      {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($row, 2) }}
                    </td>9
                  </tr>
                @endforeach

                <tr class="table-active">
                  <td>{{ __('Total Pay') }}</td>
                  <td>:</td>
                  <td class="text-right">
                    <strong>
                      {{ auth()->user()->entity->currency->currency_symbol  }}  {{ number_format($lineItem[__('Total Pay')], 2) }}
                    </strong>
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td colspan="3" style="border-bottom: 1px solid #000000;">
                    <div style="font-size: 10px!important" class="text-center">
        <span class="text-center">
          <strong>{{ strtoupper($company['company_name'])  }}</strong> <br>
          {{ $company['company_address'] }} <br>
          Phone {{ $company['company_phone'] }} | E-mail {{ $company['company_email'] }}
        </span>
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
