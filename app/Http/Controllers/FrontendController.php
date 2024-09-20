<?php

namespace App\Http\Controllers;

use App\Models\banners;
use App\Models\Blogs;
use App\Models\PaymentMethod;
use App\Models\TicketOptionsPrizes;
use Illuminate\Http\Request;
use App\Models\campaign;
use App\Models\CampaignCoupons;
use App\Models\CampaignGallery;
use App\Models\CampaignGifts;
use App\Models\CampaignMenu;
use App\Models\CampaignMeta;
use App\Models\CampaignUsers;
use App\Models\Donations;
use App\Models\EarlyBird;
use App\Models\GrandPrizes;
use App\Models\Prizes;
use App\Models\SponsorshipOpportunities;
use App\Models\Teams;
use App\Models\TicketOptions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    

    public function index(Request $request)
    {
        return view('frontend/home');
    }
    public function thank_you()
    {
        return view('frontend/templates/general-template/thank-you');
    }

    public function create_team(Request $request, Teams $teams){
        
        $campaignId = $request->input('campaign_id');
        $TeamId = $request->input('teamId') ?? null;
        // echo $TeamId;die;
        $validator = Validator::make($request->all(), [
            'first_name'  => 'required|string',
            'last_name'  => 'nullable|string',
            'display_name'  => 'required|string',
            'email'  => 'required|string',
            'slug'  => 'nullable|string',
            'phone_system_code'  => 'nullable|numeric',
            'goal'  => 'required|numeric',
            'currency'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
       

        DB::beginTransaction();

        try {
            $slug = str_replace(' ', '_', $request->first_name) . '-' . str_replace(' ', '_', $request->last_name) . '_' . rand(1,400);

            $Teams = Teams::updateOrCreate(
                ['id' => $TeamId],
                [
                    'campaign_id'  => $request->campaign_id,
                    'first_name'  => $request->first_name,
                     'last_name'  => $request->last_name,
                     'display_name'  => $request->display_name,
                     'email'  => $request->email,
                     'slug'  => $slug,
                     'phone_system_code'  => $request->phone_system_code,
                     'goal'  => $request->goal,
                     'currency'  =>  $request->currency
                ]
            );
            DB::commit();
            // return response()->json(['teams' => $Teams], 201);
            return redirect()->back()->with('success', 'Campaign created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error('Error while Creating team: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create team. ' . $e->getMessage());
            // return response()->json(['error' => 'Failed to update.', 'messages' => $e->getMessage()], 500);
        }
    
    }
    public function raffle(Request $request, Campaign $campaign)
    {


        $payment_env = config('app.payment_env');
        $campaign_Users = CampaignUsers::where('campaign_id', $campaign->id)->get();

        $payment_gateways = PaymentMethod::where('campaign_id', $campaign->id)->where('environment', $payment_env)->where('status', 1)->get();

        // dd($payment_gateways);


        $earlyBird = EarlyBird::all();
        $banners = banners::where('campaign_id', $campaign->id)->get();
        $campaign_gifts = CampaignGifts::where('campaign_id', $campaign->id)->get();
        $campaign_gallery = CampaignGallery::where('campaign_id', $campaign->id)->get();
        $blogs = Blogs::all();
        $main_Menu = CampaignMenu::where('type', 'primary')->get();
        $secondary_Menu = CampaignMenu::where('type', 'secondary')->get();
        $packages = TicketOptions::getCustomPrices($campaign->id);


        // $customPrices = TicketOptions::where('campaign_id', $campaign->id)->get();
        // dd($packages);
        // $packages = $customPrices->map(function ($ticketOption) {
        //     $prizesCount  = Prizes::count();
        //     $totalPrizes = TicketOptionsPrizes::join('prizes', 'prizes.id', '=', 'ticket_options_prizes.prize_id')
        //         ->where('ticket_options_prizes.ticket_option_id', $ticketOption->id)
        //         ->where('prizes.type', 'prize')
        //         ->count();
        //     return [
        //         'id' => $ticketOption->id,
        //         'amount' => $ticketOption->price,
        //         'previousEntries' => $ticketOption->entries,
        //         'entry' => $ticketOption->entries,
        //         'couponApplied' => false,
        //         'group_id' => $ticketOption->group_id,
        //         'prizesAmount' => $prizesCount,
        //         'icon' => $ticketOption->icon,
        //         'grandPrize' => $ticketOption->grandPrize ? $ticketOption->grandPrize->amount : null,
        //         'prizes' => $totalPrizes,
        //     ];
        // });


        $stpData = $campaign->campaign_split_the_pot->map(function ($stp) {
            return [
                'title' => $stp->title,
                'amount' => $stp->amount,
                'entries' => $stp->entries,

            ];
        });

        $campaignMenuData = $campaign->menu->map(function ($menu) {
            return [
                'title' => $menu->title,
                'url' => $menu->url,
                'type' => $menu->type,
            ];
        });
        $sponserData = $campaign->sponsorship_opportunities->map(function ($sponsorship) {
            return [
                'id' => $sponsorship->id,
                'title' => $sponsorship->title,
                'imageURL' => $sponsorship->image,
                'amount' => $sponsorship->amount,
            ];
        });

        $ourSponserData = $campaign->our_sponsors->map(function ($sponsorship) {
            return [
                'id' => $sponsorship->id,
                'title' => $sponsorship->title,
                'imageURL' => $sponsorship->image,
            ];
        });

        $CampaignTeamsData = $campaign->teams->map(function ($teams) {
            return [
                'first_name' => $teams->first_name,
                'last_name' => $teams->last_name,
                'display_name' => $teams->display_name,
                'email' => $teams->email,
                'slug' => $teams->slug,
                'phone_system_code' => $teams->phone_system_code,
                'goal' => $teams->goal,
                'currency' => $teams->currency,
            ];
        });
        $bannerData = $banners->map(function ($banner) {
            $isImgBg = $banner->banner_type === 'text';
            if ($banner->banner_type === 'file') {
                $bgImg =  $banner->file_desktop_image;
            } else {
                $bgImg =  $banner->text_desktop_image;
            }
            return [
                'type' => $banner->banner_type,
                'mainContent' => $banner->text_main_content ?? "ENTER NOW FOR A CHANCE TO WIN",
                'subContent' => $banner->text_sub_content ?? "Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque,",
                'buttonText' => $banner->text_button ?? "View Prizes",
                'buttonLink' => $banner->text_button_link ?? "#",
                'buttonSecondaryText' => $banner->text_secondary_button ?? "View Prizes",
                'buttonSecondaryLink' => $banner->text_secondary_button_link ?? "#",
                'prizeImage' => $banner->prize_image ?? '',
                'bgImg' => $bgImg,
                'isImgBg' => $isImgBg,
                'titleAmount' => $banner->text_amount ?? "$100,000!",
                'buttonClass' => empty($banner->text_button) ? 'd-none' : '',
                'title' => $banner->file_title ?? 'About 100k Goral',
                'link' => "#aboutSection",
                'imageURL' => $bgImg ?? '',
                'button' => [
                    [
                        'text' => $banner->text_button ?? "View Prizes",
                        'link' => $banner->text_button_link ?? "#",
                        'class' => empty($banner->text_button) ? 'd-none' : ''
                    ]
                ]
            ];
        });
        //dd($bannerData);
       
        $CampaignGiftsData = $campaign_gifts->map(function ($gift) {
            return [
                'id' => $gift->id,
                'title' => $gift->name,
                'imageURL' => $gift->image,
                'amount' => $gift->amount,
                'details' => $gift->details,
                'draw_date' => $gift->draw_date,
            ];
        });
        $splitPot = $campaign->campaign_split_the_pot->map(function ($stp) {
            return [
                'id' => $stp->id,
                'title' => $stp->title,
                'amount' => $stp->amount,
                'entries' => $stp->entries,
            ];
        });
        $mainMenu = $main_Menu->map(function ($primary) {
            return [
                'id' => $primary->id,
                'text' => $primary->title,
                'link' => $primary->url,
            ];
        });
        $secondaryMenu = $secondary_Menu->map(function ($secondary) {
            return [
                'id' => $secondary->id,
                'text' => $secondary->title,
                'link' => $secondary->url,
            ];
        });
        // $donations = new Donations();
        $donationData = Donations::getCampaignDonations($campaign->id);
        // dd($donationData);
        $bannerData = $bannerData[0] ?? [
            'type' => 'text',
                'mainContent' =>  "ENTER NOW FOR A CHANCE TO WIN",
                'subContent' =>  "Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque,",
                'buttonText' =>  "View Prizes",
                'buttonLink' =>  "#",
                'bgImg' => 'https://cdn.100kgoral.org/V2/blogs/1722249678_english-writing-assignment1.jpg',
                'isImgBg' => 'https://cdn.100kgoral.org/V2/blogs/1722249678_english-writing-assignment1.jpg',
                'titleAmount' =>  "$100,000!",
                'buttonClass' => '',
                'title' => 'About 100k Goral',
                'link' => "#aboutSection",
                'imageURL' => '',
                'button' => [
                    [
                        'text' =>  "View Prizes",
                        'link' =>"#",
                        'class' => ''
                    ]
                ]
        ];
        $gallery = CampaignGallery::getCampaingGallery($campaign->id);
        $campaignSettings = CampaignMeta::getCampaignSetting($campaign->id);
        $TicketOptionsPrizes = TicketOptionsPrizes::getUniqueRecordsByPrizeId($campaign->id);
        $campaignPrizes = collect($TicketOptionsPrizes)->map(function ($prize, $index) {
            $ticketOption = new TicketOptionsPrizes();
            $subPriceIDsJson = $ticketOption->getTicketId($prize->prize_id);
            return [
                'number' => $index + 1,
                'id' => $prize->id,
                'imageURL' =>  Prizes::where('id', $prize->prize_id)->first()->image,
                'title' => Prizes::where('id', $prize->prize_id)->first()->name,
                'packageIds' => $subPriceIDsJson,

            ];
        });

        $grandPrizes = GrandPrizes::first();
        $ticketOptions = new TicketOptions();
        $grandPrizeIds = $ticketOptions->getGrandPrizeIds($campaign->id);
        $grandPrize = [
            'number' =>  1,
            'packageIds' => $grandPrizeIds,
            //'imageURL' =>  $grandPrizes->image,
            'defaultAmount' => 100000,
            'description' => $grandPrizes->title,

        ];


        $fabulousPrizes = $campaignPrizes->count();
        $largestDonation = Donations::where('campaign_id', $campaign->id)->max('amount');
        $totalDonors = Donations::where('campaign_id', $campaign->id)->count();
        $ticketsSold = Donations::where('campaign_id', $campaign->id)->sum('entries');
        $countersStatistic = [
            ['id' => 'fabulousPrizes', 'text' => 'Fabulous Prizes', 'value' => $fabulousPrizes],
            ['id' => 'totalDonors', 'text' => 'Total<br>Donors', 'value' => $totalDonors],
            ['id' => 'ticketsSold', 'text' => 'Tickets<br>Sold', 'value' => $ticketsSold],
            ['id' => 'largestDonation', 'text' => 'Largest<br>Donation', 'value' => $largestDonation],
        ];

        $campaign->load('media', 'meta', 'payments', 'organization', 'openGraph', 'teams');

        $raffleOffer = [
            'drawingDate' => (new \DateTime('2023-10-30'))->format(DATE_ISO8601),
            'progressPercentage' => 87,
            'amount' => 103332.44,
            'targetAmount' => $campaign->meta->goal,
            'showBonusAmount' => $campaign->meta->show_goal,
            'bonusAmount' => $campaign->meta->bonus_amount,
            'endDate' => $campaign->meta->end_date,
            'button' => [['text' => 'Enter The Raffle', 'link' => '#']],
        ];
        $about = [
            'title' => $campaign->meta->intro_content_enabled == 1 ? $campaign->meta->intro_content_header : 'About Raffle',
            'description' => $campaign->meta->intro_main_content,
            'additionalContent' => json_encode($campaign->meta->additional_content),
            'button' => [
                [
                    'text' => $campaign->meta->button_content,
                    'link' => '#additionalContent',
                    'class' => empty($campaign->meta->button_content) ? 'd-none' : '',
                ]
            ],
        ];
        $blogsData = $blogs->map(function ($blog) use ($campaign){
           return [
                 'id'=> $blog->id,
                 'title'=> $blog->title,
                 'slug'=> $blog->slug,
                 'image'=> $blog->image,
                 'description'=> $blog->description,
                 'date'=> $blog->created_at,
           ];
        });
      




        return view('frontend/layout', compact(
            'campaign',
            'mainMenu',
            'secondaryMenu',
            'packages',
            'campaign_Users',
            'payment_gateways',
            'bannerData',
            'sponserData',
            'gallery',
            'campaignSettings',
            'CampaignTeamsData',
            'CampaignGiftsData',
            'donationData',
            'campaignMenuData',
            'splitPot',
            'campaignPrizes',
            'grandPrize',
            'countersStatistic',
            'about',
            'blogsData',
            'ourSponserData',

        ));
        // return view('frontend/templates/raffle-template/home', compact('campaign', 'ticketOptions', 'campaign_Users'));
    }
}
