<table style="border-collapse: collapse;width: 100%;">
  <tbody>
  @foreach ($data as $key => $value)
    <tr>
      <td style="border: 1px solid #5e5b5b;text-align: left;padding: 4px;width:10%">{{ $data[$key] }} </td>
      <td style="border: 1px solid #5e5b5b;text-align: left;padding: 4px;width:30%">{{ $data[$key] }} </td>
    </tr>
  @endforeach
  </tbody>

</table>
