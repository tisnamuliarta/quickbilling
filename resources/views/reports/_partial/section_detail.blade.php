<table style="width: 100%;">
  @foreach($items as $index => $item)
    <tr>
      <td colspan="2">
        {{ $index }}
        <table class="v-data-table-custom" style="width: 100%; padding-left: 10px;">
          @foreach($item['accounts'] as $idx => $value)
            <tr>
              <td>{{ $value->name }}</td>
              <td class="text-right">
                {{ auth()->user()->entity->currency->currency_symbol . ' '. number_format($value->closingBalance, 2)  }}
              </td>
            </tr>
          @endforeach
        </table>
      </td>
    </tr>
  @endforeach
</table>
