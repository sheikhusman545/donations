@section('title', 'Me Template')
@include('frontend.templates.me-template.includes.header')
@php @endphp
<main>
    <style>
        .banner-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .banner-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: flex-start;
            align-items: flex-start;
            text-align: left;
            flex-basis: 100%;
            padding: 80px;
        }

        .banner-section img {
            max-width: 100%;
            height: auto;
            /* Ensure image is responsive */
        }

        .logos {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 20px;
        }

        a.btn.btn-primary.btn-black {
            background: black !important;
            border-radius: inherit !important;
        }

        .promo__title {
            font-size: 2rem !important;
            font-weight: 700;
            color: black !important;
            ;
        }

        .promo__title mark {
            color: black;
            font-size: 2rem;
            font-weight: 700;
        }

        .promo__title mark::before {
            content: '$';
        }

        @media only screen and (max-width: 768px) {
            .banner-container {
                flex-direction: column !important;
            }
        }
    </style>
    {{-- <style>
        .banner-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 2rem;
            /* Adjust gap between sections as needed */
            height: 100%;
        }

        /* .banner-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            justify-content: space-around;
        } */

        .banner-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: flex-start;
            align-items: flex-start;

        }

        .banner-section img {
            max-width: 100%;
            /* Ensure image is responsive */
            height: auto;
        }

        .logos {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 20px;
        }

        a.btn.btn-primary.btn-black {
            background: black !important;
            border-radius: inherit !important;
        }
    </style>
--}}
    <section class="banner-image container">
        <div class="banner-container d-flex  align-items-center"
            style="margin-top:100px; min-height:400px;  border-radius: 0px 0px 50px 50px; background-size: cover; background-position: center center;">
            <div class="banner-section s-1 d-flex flex-column ">
                <h1></h1>
                <h6></h6>
                <a href="" class="btn btn-primary btn-black"></a>
                <a href="" class=" secondry-link"></a>
            </div>
            <div class="banner-section bannerSection s-2 d-flex flex-column" style="background-image: url('{{ asset('assets/media/misc/background.png') }}');background-position:center">
                <div class="prize-image">
                    <img src="" alt="Prize" class="img-fluid">
                </div>
               <div class="our-sponsor-logo d-flex gap-20"></div>
            </div>
        </div>
    </section>

    <style>
        .cart {
            display: none;
        }
    </style>
</main>
@include('frontend.templates.me-template.includes.footer')
{{-- @endsection --}}

@section('scripts')

    <script type="module" src="{{ asset('assets/frontend/templates/raffle/js/main.js') }}?v={{ time() }}"></script>

@endsection
