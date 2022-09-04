<tr>
  <th>Transaction No</th>
  <th>Posting Date</th>
  <th>Account Code</th>
  <th>Account Category</th>
  <th>Account Name</th>
  <th class="text-right">Debit</th>
  <th class="text-right">Credit</th>
</tr>

@foreach($report as $item)
  @php $total_debit = 0 @endphp
  @php $total_credit = 0 @endphp

  @foreach($item->ledgers as $ledger)
    @php $total_debit += ($ledger->entry_type == 'D') ? $ledger->amount : 0 @endphp
    @php $total_credit += (($ledger->entry_type == 'C') ? $ledger->amount : 0) @endphp
    <tr>
      <td class="disable-wrap">{{ $item->transaction_no  }}</td>
      <td class="disable-wrap">{{ $ledger->posting_date  }}</td>
      <td class="disable-wrap">{{ $ledger->postAccount->code }}</td>
      <td class="disable-wrap">{{ $ledger->postAccount->account_type }}</td>
      <td class="disable-wrap">{{ $ledger->postAccount->name }}</td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol
            . ' '.
            number_format(($ledger->entry_type == 'D') ? $ledger->amount : 0, 2)
            }}
      </td>
      <td
        class="text-right disable-wrap">
        {{ auth()->user()->entity->currency->currency_symbol
            . ' '.
            number_format(($ledger->entry_type == 'C') ? $ledger->amount : 0, 2)
            }}
      </td>
    </tr>
  @endforeach
  <tr>
    <td colspan="5" class="disable-wrap">{{ $item->narration  }}</td>
    <td class="text-right" style="border-top: 1px solid #222222;">
      <strong>
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total_debit, 2)  }}
      </strong>
    </td>
    <td class="text-right" style="border-top: 1px solid #222222;">
      <strong>
        {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($total_credit, 2)  }}
      </strong>
    </td>
  </tr>
  <tr>
    <td colspan="7"></td>
  </tr>
@endforeach
