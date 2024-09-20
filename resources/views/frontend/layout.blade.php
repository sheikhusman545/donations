@if ($campaign->template == 'raffle')
@include('frontend.templates.raffle-template.home')
@elseif ($campaign->template == 'general')
@include('frontend.templates.general-template.home')
@elseif ($campaign->template == 'event')
@include('frontend.templates.event-template.home')
@elseif ($campaign->template == 'me')
@include('frontend.templates.me-template.home')
@endif
<link rel="stylesheet" href="{{ asset('assets/frontend/templates/raffle/css/style.css') }}">



{{-- <link rel="stylesheet" href="{{ asset('assets/frontend/templates/raffle/css/general-style.css') }}"> --}}
{{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
@include('frontend.checkout')
@include('frontend.add-team')


@include('frontend.js-data')

<style>
    #custom_recurring_cycle {
        width: 20px !important;
    }

</style>
{{-- <script src="{{ asset('assets/frontend/templates/raffle/js/scripts.js') }}?v={{ time() }}"></script> --}}

@yield('scripts')
