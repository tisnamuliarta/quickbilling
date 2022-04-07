@component('mail::message')
Dear, {{ $documents->contact_name }},

{{ $messages }}

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
