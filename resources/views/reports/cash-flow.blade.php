<tr>
  <th>{{ __('Operations Cash Flow') }}</th>
  <th></th>
</tr>

@include('reports._partial.section_sub', ['total' => $report['balances']['PROFIT'], 'title' => __('Net Profit')])
@include('reports._partial.section_sub', ['total' => $report['balances']['PROVISIONS'], 'title' => __('Provisions')])
@include('reports._partial.section_sub', ['total' => $report['balances']['RECEIVABLES'], 'title' => __('Receivables')])
@include('reports._partial.section_sub', ['total' => $report['balances']['PAYABLES'], 'title' => __('Payables')])
@include('reports._partial.section_sub', ['total' => $report['balances']['TAXATION'], 'title' => __('Taxation')])
@include('reports._partial.section_sub', ['total' => $report['balances']['CURRENT_ASSETS'], 'title' => __('Current Assets')])
@include('reports._partial.section_sub', ['total' => $report['balances']['CURRENT_LIABILITIES'], 'title' => __('Current Liabilities')])

@include('reports._partial.section_total', ['total' => $report['results']['OPERATIONS_CASH_FLOW'], 'title' => __('Total Operations Cash Flow')])

<tr>
  <th>{{ __('Investment Cash Flow') }}</th>
  <th></th>
</tr>

@include('reports._partial.section_sub', ['total' => $report['balances']['NON_CURRENT_ASSETS'], 'title' => __('Non Current Assets')])

@include('reports._partial.section_total', ['total' => $report['results']['INVESTMENT_CASH_FLOW'], 'title' => __('Total Investment Cash Flow')])

<tr>
  <th>{{ __('Financing Cash Flow') }}</th>
  <th></th>
</tr>

@include('reports._partial.section_sub', ['total' => $report['balances']['NON_CURRENT_LIABILITIES'], 'title' => __('Non Current Liabilities')])
@include('reports._partial.section_sub', ['total' => $report['balances']['EQUITY'], 'title' => __('Equity')])

@include('reports._partial.section_total', ['total' => $report['results']['FINANCING_CASH_FLOW'], 'title' => __('Total Financing Cash Flow')])

<tr>
  <th>{{ __('Net Cash Flow') }}</th>
  <th></th>
</tr>
@if(array_key_exists('START_CASH_BALANCE', $report['results']))
  @include('reports._partial.section_sub', ['total' => (array_key_exists('START_CASH_BALANCE', $report['results'])) ? $report['results']['START_CASH_BALANCE'] : 0, 'title' => __('Beginning Cash balance')])
@endif

@if(array_key_exists('NET_CASH_FLOW', $report['balances']))
  @include('reports._partial.section_sub', ['total' => $report['balances']['NET_CASH_FLOW'], 'title' => __('Net Cash Flow')])
@endif

@include('reports._partial.section_total', ['total' => $report['results']['END_CASH_BALANCE'], 'title' => __('Ending Cash balance')])
@include('reports._partial.section_total', ['total' => $report['results']['CASHBOOK_BALANCE'], 'title' => __('Cashbook balance')])
