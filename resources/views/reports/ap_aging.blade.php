<tr>
  <th>Account</th>
  @foreach($report->Brackets as $index => $header)
    <th class="text-right">{{ $index }}</th>
  @endforeach
</tr>

@foreach($report->Accounts as $account)
  <tr>
    <td>
      {{ $account->name }}
    </td>
    @foreach($account->balances as $index => $balance)
      <td class="text-right">{{  auth()->user()->entity->currency->currency_symbol . ' '. number_format($balance, 2) }}</td>
    @endforeach
  </tr>
@endforeach

<tr>
  <th>Total</th>
  @foreach($report->Balances as $index => $header)
    <td class="text-right">
      <strong>
        {{  auth()->user()->entity->currency->currency_symbol . ' '. number_format($header, 2) }}
      </strong>
    </td>
  @endforeach
</tr>
