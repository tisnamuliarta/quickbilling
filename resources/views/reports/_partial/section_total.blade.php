<tr style="border-top: 2px solid #222 !important">
  <th>{{ $title }}</th>
  <th class="text-right" style="border-top: 2px solid #222 !important;">
    <span style="padding-right: 5px; font-size: .875rem">
      {{
        auth()->user()->entity->currency->currency_symbol . ' '.
       number_format($total, 2)
      }}
    </span>
  </th>
</tr>
