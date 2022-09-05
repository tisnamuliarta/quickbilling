<tr>
  <th>Code</th>
  <th>Account</th>
  <th>Type</th>
  <th>Category</th>
  <th class="text-right">Balance</th>
</tr>

@foreach($report as $account)
  <tr>
    <td>{{ $account->code }}</td>
    <td>{{ $account->name }}</td>
    <td>{{ $account->account_type }}</td>
    <td>{{ ($account->category) ? $account->category->name : null }}</td>
    <td class="text-right">{{  auth()->user()->entity->currency->currency_symbol . ' '. number_format($account->balance, 2) }}</td>
  </tr>
@endforeach
