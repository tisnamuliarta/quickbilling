<tr style="border-top: 2px solid #222 !important">
    <td style="padding-left: 30px;">{{ $title }}</td>
    <td class="text-right">
    <span style="padding-right: 5px; font-size: .875rem">
      {{
        auth()->user()->entity->currency->currency_symbol . ' '.
       number_format($total, 2)
      }}
    </span>
    </td>
</tr>
