@if(array_key_exists('OPERATING_REVENUES', $report['accounts']))
  <tr>
    <th>{{ __('Operating Revenues') }}</th>
    <th></th>
  </tr>
  @php $detail = (array_key_exists('OPERATING_REVENUE', $report['accounts']['OPERATING_REVENUES'])) ? $report['accounts']['OPERATING_REVENUES']['OPERATING_REVENUE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif


@if(array_key_exists('OPERATING_EXPENSE', $report['accounts']))
  <tr>
    <th>{{ __('Operating Expenses') }}</th>
    <th></th>
  </tr>

  @php $detail = (array_key_exists('OPERATING_EXPENSE', $report['accounts']['OPERATING_EXPENSES'])) ? $report['accounts']['OPERATING_EXPENSES']['OPERATING_EXPENSE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@include('reports._partial.section_total', ['total' => $report['results']['GROSS_PROFIT'], 'title' => __('Gross Profit')])


@if(array_key_exists('NON_OPERATING_REVENUE', $report['accounts']['NON_OPERATING_REVENUES']))
  <tr>
    <th>{{ __('Non Operating Revenues') }}</th>
    <th></th>
  </tr>
  @php $detail = (array_key_exists('NON_OPERATING_REVENUE', $report['accounts']['NON_OPERATING_REVENUES'])) ? $report['accounts']['NON_OPERATING_REVENUES']['NON_OPERATING_REVENUE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@include('reports._partial.section_total', ['total' => $report['results']['TOTAL_REVENUE'], 'title' => __('Total Revenue')])

@if($report['totals']['NON_OPERATING_EXPENSES'] > 0)
  <tr>
    <th>{{ __('Non Operating Expenses') }}</th>
    <th></th>
  </tr>
@endif

@if(array_key_exists('DIRECT_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES']))
  @php $detail = (array_key_exists('DIRECT_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES'])) ? $report['accounts']['NON_OPERATING_EXPENSES']['DIRECT_EXPENSE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@if(array_key_exists('OVERHEAD_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES']))
  @php $detail = (array_key_exists('OVERHEAD_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES'])) ? $report['accounts']['NON_OPERATING_EXPENSES']['OVERHEAD_EXPENSE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@if(array_key_exists('OTHER_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES']))
  @php $detail = (array_key_exists('OTHER_EXPENSE', $report['accounts']['NON_OPERATING_EXPENSES'])) ? $report['accounts']['NON_OPERATING_EXPENSES']['OTHER_EXPENSE'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@include('reports._partial.section_total', ['total' => $report['totals']['NON_OPERATING_EXPENSES'], 'title' => __('Total Non Operating Expenses')])
@include('reports._partial.section_total', ['total' => $report['results']['NET_PROFIT'], 'title' => __('Net Profit')])

