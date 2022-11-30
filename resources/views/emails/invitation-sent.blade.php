<h1>@lang('emails.greeting')</h1>
<p>@lang('emails.invitation') {{ config('app.name') }}.</p>
<p>@lang('emails.registration_link') <a href="{{ $link }}">{{ $link }}</a></p>
