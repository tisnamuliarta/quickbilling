@if(array_key_exists('ASSETS', $report['accounts']))
  <tr>
    <th>{{ __('Assets') }}</th>
    <th></th>
  </tr>

  @if(array_key_exists('NON_CURRENT_ASSET', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('NON_CURRENT_ASSET', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['NON_CURRENT_ASSET'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('CONTRA_ASSET', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('CONTRA_ASSET', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['CONTRA_ASSET'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('INVENTORY', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('INVENTORY', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['INVENTORY'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('BANK', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('BANK', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['BANK'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('CURRENT_ASSET', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('CURRENT_ASSET', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['CURRENT_ASSET'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('RECEIVEABLE', $report['accounts']['ASSETS']))
    @php $detail = (array_key_exists('RECEIVEABLE', $report['accounts']['ASSETS'])) ? $report['accounts']['ASSETS']['RECEIVEABLE'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @include('reports._partial.section_total', ['total' => $report['totals']['ASSETS'], 'title' => __('Total Assets')])
@endif


@if(array_key_exists('LIABILITIES', $report['accounts']))
  <tr>
    <th>{{ __('Liabilities') }}</th>
    <th></th>
  </tr>

  @if(array_key_exists('NON_CURRENT_LIABILITY', $report['accounts']['LIABILITIES']))
    @php $detail = (array_key_exists('NON_CURRENT_LIABILITY', $report['accounts']['LIABILITIES'])) ? $report['accounts']['LIABILITIES']['NON_CURRENT_LIABILITY'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('CONTROL', $report['accounts']['LIABILITIES']))
    @php $detail = (array_key_exists('CONTROL', $report['accounts']['LIABILITIES'])) ? $report['accounts']['LIABILITIES']['CONTROL'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('CURRENT_LIABILITY', $report['accounts']['LIABILITIES']))
    @php $detail = (array_key_exists('CURRENT_LIABILITY', $report['accounts']['LIABILITIES'])) ? $report['accounts']['LIABILITIES']['CURRENT_LIABILITY'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @if(array_key_exists('PAYABLE', $report['accounts']['LIABILITIES']))
    @php $detail = (array_key_exists('PAYABLE', $report['accounts']['LIABILITIES'])) ? $report['accounts']['LIABILITIES']['PAYABLE'] : [] @endphp
    <tr>
      <td colspan="2">
        @include('reports._partial.section_detail', ['items' => $detail])
      </td>
    </tr>
  @endif

  @include('reports._partial.section_total', ['total' => $report['totals']['LIABILITIES'], 'title' => __('Total Liabilities')])
@endif

@if(array_key_exists('RECONCILIATION', $report['accounts']['RECONCILIATION']))
  <tr>
    <th>{{ __('Reconciliation') }}</th>
    <th></th>
  </tr>

  @php $detail = (array_key_exists('RECONCILIATION', $report['accounts']['RECONCILIATION'])) ? $report['accounts']['RECONCILIATION']['RECONCILIATION'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>

  @include('reports._partial.section_total', ['total' => $report['totals']['RECONCILIATION'], 'title' => __('Total Reconciliation')])
@endif

@include('reports._partial.section_total', ['total' => $report['results']['NET_ASSETS'], 'title' => __('Net Assets')])

@if(array_key_exists('EQUITY', $report['accounts']['EQUITY']))
  <tr>
    <th>{{ __('Equity') }}</th>
    <th></th>
  </tr>

  @php $detail = (array_key_exists('EQUITY', $report['accounts']['EQUITY'])) ? $report['accounts']['EQUITY']['EQUITY'] : [] @endphp
  <tr>
    <td colspan="2">
      @include('reports._partial.section_detail', ['items' => $detail])
    </td>
  </tr>
@endif

@include('reports._partial.section_total', ['total' => $report['results']['TOTAL_EQUITY'], 'title' => __('Total Equity')])



