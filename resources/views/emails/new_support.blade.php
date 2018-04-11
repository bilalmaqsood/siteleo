@component('mail::message')
# New event on the site.

{!! $message !!} <br>

@component('mail::button', ['url' => env('APP_URL').'/admin'])
    Admin panel
@endcomponent

Thanks, <br>
{{ config('app.name') }}
@endcomponent