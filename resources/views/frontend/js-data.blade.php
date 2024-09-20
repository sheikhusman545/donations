<script>
    window.siteContent = {!! json_encode([
        'mainMenu' => $mainMenu,
        'secondaryMenu' => $secondaryMenu,
        'logo' => [
            'img' => $campaign->media->logo_url ? $campaign->media->logo_url : 'images/logo.png',
            'alt' => '100k Goral Logo',
            'text' => '100k Goral',
        ],
        'promo' => $bannerData,
        'bannerSection' => $bannerData,
        'ourSponsors' => $ourSponserData,
        'raffleOffer' => [
            'drawingDate' => (new \DateTime('2023-10-30'))->format(DATE_ISO8601),
            'progressPercentage' => 87,
            'amount' => 103332.44,
            'targetAmount' => 110000,
    
            'button' => [['text' => 'Enter The Raffle', 'link' => '#']],
        ],
        'countersStatistic' => $countersStatistic,
        // [
        //     ['id' => 'fabulousPrizes', 'text' => 'Fabulous Prizes', 'value' => 10],
        //     ['id' => 'totalDonors', 'text' => 'Total\nDonors', 'value' => 27],
        //     ['id' => 'ticketsSold', 'text' => 'Tickets\nSold', 'value' => 7],
        //     ['id' => 'largestDonation', 'text' => 'Largest\nDonation', 'value' => 7500],
        // ],
        'about' => [
            'title' => $campaign->title,
            'description' => '',
            'button' => [['text' => 'More About 100k', 'link' => '#']],
        ],
        'tickets' => [
            'title' => 'Choose your tickets',
            'packages' => $packages,
    
            'prizes' => $campaignPrizes,
            'grandPrize' => $grandPrize,
            'gifts' => $CampaignGiftsData,
        ],
        'splitPot' => $splitPot,
        'cart' => [
            'heading' => 'Checkout',
            'ticketsTitle' => 'Your Donation:',
            'giftsTitle' => 'Selected Gifts',
        ],
        'about' => $about,
        'aboutCampaign' => [
            'heading' => 'About 100k Goral',
            'text' => '',
            'button' => [
                [
                    'text' => 'More About 100k',
                    'link' => '#',
                ],
            ],
        ],
        'campaignSettings' => $campaignSettings,
        'partners' => [
            'donors' => $donationData,
            'teamMembers' => [],
            'teamDoners' => [],
        ],
        'sponsorship' => $sponserData,
    
        'gallery' => $gallery,
    
        'teamTitle' => [
            'title' => 'Teams',
            'slug' => 'teams',
            'donationsCount' => 0,
            'teamsCount' => 0,
            'teamsDonationCount' => 0,
        ],
        'blogsData' => $blogsData,
        'template' => $campaign->template,
    
        'footer' => [
            'footerTitle' => '100k Goral',
            'addressLine1' => '78 Division Lane',
            'addressLine2' => 'Brooklyn, NY 11229',
            'country' => 'USA',
            'email' => 'info@cchf-raffle.com',
            'phone' => '929 485 9382 23',
            'terms' => [
                'Compliance Notice 18 U.S.C Section 5847.',
                'You must be 18 years or older to participate.',
                '© 2024 / 100k Goral. All rights reserved.',
            ],
        ],
    ]) !!};
</script>
