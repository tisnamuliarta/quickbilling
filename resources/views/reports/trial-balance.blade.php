<tr>
  <th>Account</th>
  <th class="text-right">Debit</th>
  <th class="text-right">Credit</th>
</tr>

@foreach($report['accounts']['BALANCE_SHEET'] as $index => $balance)
  @foreach($report['accounts']['BALANCE_SHEET'][$index]['accounts'] as $acc)
    <tr>
      <td>{{ $acc->name  }}</td>
      <td
        class="text-right">{{ auth()->user()->entity->currency->currency_symbol . ' '.  number_format($acc->closingBalance[1]) }}</td>
      <td class="text-right">0</td>
    </tr>
  @endforeach
@endforeach

@foreach($report['accounts']['INCOME_STATEMENT'] as $index => $balance)
  @foreach($report['accounts']['INCOME_STATEMENT'][$index]['accounts'] as $acc)
    <tr>
      <td>{{ $acc->name  }}</td>
      <td class="text-right">0</td>
      <td
        class="text-right">{{ auth()->user()->entity->currency->currency_symbol . ' '.  number_format($acc->closingBalance[1]) }}</td>
    </tr>
  @endforeach
@endforeach

<tr>
  <td>
    <strong>Total</strong>
  </td>
  <td
    class="text-right"
    style="border-top: 1px solid #222222;"
  >
    <strong>
      {{ auth()->user()->entity->currency->currency_symbol
            . ' '.
            number_format($report['results']['BALANCE_SHEET']['debit'] - $report['results']['BALANCE_SHEET']['credit']) }}
    </strong>
  </td>
  <td
    class="text-right"
    style="border-top: 1px solid #222222;"
  >
    <strong>
      {{
        auth()->user()->entity->currency->currency_symbol
        . ' '.
        number_format($report['results']['INCOME_STATEMENT']['debit'] - $report['results']['INCOME_STATEMENT']['credit']) }}
    </strong>
  </td>
</tr>
