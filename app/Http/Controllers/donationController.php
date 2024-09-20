<?php

namespace App\Http\Controllers;

use App\Models\campaign;
use App\Models\CampaignSplitPot;
use App\Models\Donations;
use App\Models\DonationSplitPot;
use App\Models\Teams;
use App\Models\TransactionDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class donationController extends Controller
{
    public function index(Request $request)
    {
        $seasonId = $request->session()->get('season_id');
        $campaigns = campaign::where('season_id',$seasonId)->get();
        $donations = Donations::with('campaign')->get();
        return view(
            'admin.donation.donation',
            [
                'donations' => $donations,
                'campaigns' => $campaigns
            ]
        );
    }
    public function getDonationData(Request $request)
    {
        $data = Donations::with('campaign');
        // ->select('donations.*');
        if ($request->has('campaign_id') && $request->campaign_id != '') {
            $data->where('campaign_id', $request->campaign_id);
        }
        if ($request->has('status') && $request->status != '') {
            $data->where('status', $request->status);
        }
        if ($request->has('type') && $request->type != '') {
            $data->where('type', $request->type);
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $data->whereDate('created_at', '>=' ,$request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $data->whereDate('created_at', '<=' ,$request->end_date);
        }

        return Datatables::of($data)
        ->addColumn('campaign_title', function ($row) {
            return $row->campaign ? $row->campaign->camp_title : '';
        })
        ->addColumn('donor_name', function ($row) {
            return $row->donor_first_name . ' ' . $row->donor_last_name;
        })
        ->addColumn('team_name', function ($row) {
            $team = Teams::select('display_name')->where('id', $row->team_id)->first();
            return $team ? $team->display_name : '';
        })
        ->addColumn('action', function ($row) {
           $detailUrl = route('admin.donationsDetails', $row->id);
           return '
            <a href="'. $detailUrl.'" style="font-size: 10px;white-space:nowrap;" class="btn btn-success"> <i class="fas fa-info-circle"></i> Details</a>
           ';
        })
        ->make(true);
    }

    public function details($id)
    {

        $details = Donations::where('id', $id)->first();
        $campaign_id = $details->campaign_id;
        $teams = Teams::where('campaign_id', $campaign_id)->get();
        $campaign = campaign::select('camp_title')->where('id', $campaign_id)->first();
        $stp = DonationSplitPot::where('donation_id', $id)->first() ?? '';
        // dd($stp);
        return view(
            'admin.donation.donationDetails',
            [
                'details' => $details,
                'campaign' => $campaign,
                'teams' => $teams,
                'stp' => $stp
            ]
        );
    }
    public function referrers()
    {
        return view(
            'admin.donation.referrers'
        );
    }
    public function changeDonationTeam(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'donationId' => 'required|numeric',
            'team_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $donationId = $request->donationId;
            $donation = Donations::findOrFail($donationId);
            $donation_team = $donation->update(
                [
                    'team_id' => $request->team_id,
                ]
            );

            DB::commit();

            return redirect()->route('admin.donationsDetails', ['id' => $donationId])->with('success', 'Team Changed successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while change team: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to change team. ' . $e->getMessage());
        }
    }
    public function offline_donations(Request $request)
    {
        $seasonId = $request->session()->get('season_id');
        return view(
            'admin.donation.offlineDonation',
            [
                'campaigns' => Campaign::where('season_id', $seasonId)->select('id', 'camp_title')->get()
            ]
        );
    }
    public function edit_donation_notes(Request $request, Donations $donations)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'notesId' => 'required|numeric',
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $notesId = $request->notesId;
            $notes = Donations::findOrFail($notesId);
            $donation_notes = $notes->update(
                [
                    'notes' => $request->notes,
                ]
            );

            DB::commit();

            return redirect()->route('admin.donationsDetails', ['id' => $notesId])->with('success', 'Notes Updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while updating notes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to updating notes. ' . $e->getMessage());
        }
    }

    public function refundDonation(Request $request, Donations $donations)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'donationId' => 'required|numeric',
            // 'status' => 'required|string',
            'type' => 'required|string',
            'refund_confirm' => 'required|string',
            'refund_notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $donationId = $request->donationId;
            $donation = Donations::findOrFail($donationId);
            $donationRefund = $donation->update(
                [
                    'status' => 'Refunded',
                    'refund_notes' => $request->refund_notes
                ]
            );

            DB::commit();

            return redirect()->route('admin.donationsDetails', ['id' => $donationId])->with('success', 'Donation Refunded successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while refund donation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to Refund Donation. ' . $e->getMessage());
        }
    }

    public function edit_donation(Request $request, Donations $donations)
    {
        $request->merge([
            'is_anonymous' => $request->input('is_anonymous', 0)
        ]);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'donationId' => 'required|numeric',
            'is_anonymous' => 'numeric|min:0',
            'donor_first_name' => 'required|string',
            'donor_last_name' => 'required|string',
            'donor_email' => 'required|string',
            'usd_amount' => 'required|numeric',
            'amount' => 'required|numeric',
            'stp_usd_amount' => 'nullable|numeric',
            'stp_amount' => 'nullable|numeric',
            'stp_entries' => 'nullable|numeric',
            'entries' => 'nullable|numeric',
            'donor_phone' => 'nullable|numeric',
            'address' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'street' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $donationId = $request->donationId;
            $donation = Donations::findOrFail($donationId);
            $donations = $donation->update(
                [
                    'is_anonymous' => $request->is_anonymous,
                    'donor_first_name' => $request->donor_first_name,
                    'donor_last_name' => $request->donor_last_name,
                    'donor_email' => $request->donor_email,
                    'usd_amount' => $request->usd_amount,
                    'amount' => $request->amount,
                    'entries' => $request->entries,
                    'donor_phone' => $request->donor_phone,
                    'address' => $request->address,
                    'zipcode' => $request->zipcode,
                    'street' => $request->street,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'comments' => $request->comments,

                ]
            );
            $donationStp = DonationSplitPot::updateOrCreate(
                [
                    'donation_id' => $donationId,
                ],
                [
                    'stp_usd_amount' => $request->stp_usd_amount,
                    'stp_amount' => $request->stp_amount,
                    'stp_entries' => $request->stp_entries
                ]
            );

            DB::commit();

            return redirect()->route('admin.donationsDetails', ['id' => $donationId])->with('success', 'Notes Updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while edit donation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed edit Donation. ' . $e->getMessage());
        }
    }

    public function create(Request $request, Donations $donations)
    {
        // echo "<pre>";
        // print_r($request->all());die;
        $request->merge([
            'is_anonymous' => $request->input('is_anonymous', 0)
        ]);
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|numeric',
            'donor_first_name' => 'required|string',
            'donor_last_name' => 'required|string',
            'donor_email' => 'required|string',
            'donor_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'team_id' => 'nullable|numeric',
            'amount' => 'required|numeric',
            'usd_amount' => 'required|numeric',
            'currency' => 'required|string',
            'notes' => 'nullable|string',
            'campaign_split_pot_id' => 'nullable|numeric',
            'is_anonymous' => 'numeric|min:0',
            'recurring' => 'required|string',
            'recurring_intervals' => 'nullable|numeric',
            'type' => 'required|string',
            'season_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect('admin/offline-donations')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $Donations = Donations::create(
                [
                    'campaign_id' => $request->campaign_id,
                    'donor_first_name' => $request->donor_first_name,
                    'donor_last_name' => $request->donor_last_name,
                    'donor_email' => $request->donor_email,
                    'donor_phone' => $request->donor_phone,
                    'address' => $request->address,
                    'team_id' => $request->team_id,
                    'amount' => $request->amount,
                    'usd_amount' => $request->usd_amount,
                    'currency' => $request->currency,
                    'notes' => $request->notes,
                    'is_anonymous' => $request->is_anonymous,
                    'recurring' => $request->recurring,
                    'recurring_intervals' => $request->recurring_intervals,
                    'type' => $request->type,
                    'season_id' => $request->season_id
                ]
            );
            $DonationsId = $Donations->id;
            $DonationSplitPot = DonationSplitPot::create([
                'donation_id' => $DonationsId,
                // 'campaign_split_pot_id' => $request->campaign_split_pot_id
            ]);
            DB::commit();

            return redirect()->route('admin.offline-donations')->with('success', 'Donations added successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error importing donations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error importing donations. Please try again.' . $e->getMessage());
        }
    }

    public function handlePostPayment($request, $campaignId, $paymentIntent)
    {
// dd($paymentIntent);

        DB::beginTransaction();

        try {
            if ($request->don_recurring == 1) {
                // $subscription_id = $paymentIntent->id;
                $subscription_id = null;
                $recurring_intervals = $request->recurring_intervals;
            } else {
                $recurring_intervals = null;
                $subscription_id = null;
            }

            $Donations = Donations::create(
                [
                    'campaign_id' => $campaignId,
                    'donor_first_name' => $request->donor_first_name,
                    'donor_last_name' => $request->donor_last_name,
                    'donor_email' => $request->donor_email,
                    'donor_phone' => $request->donor_phone,
                    'address' => $request->address,
                    'comments' => $request->comments,
                    'currency' => $request->currency,
                    'city' => $request->city,
                    'recurring' => $request->recurring ?? 'OneTime',
                    'state' => $request->state,
                    'zipcode' => $request->zipcode,
                    'country' => $request->country,
                    'amount' => $request->amount,
                    'usd_amount' => $request->amount,
                    'payment_gateway' => $request->pay_with,
                    'paid_by' => 1,
                    'recurring_intervals' => $recurring_intervals,
                    'subscription_id' => $subscription_id,
                    'season_id' => $request->season_id,

                ]
            );
            $DonationsId = $Donations->id;
            $DonationSTP = DonationSplitPot::create([
                'donation_id' => $DonationsId,
                // 'campaign_split_pot_id' => $request->campaign_split_pot_id
            ]);
            $paymentDatils = $paymentIntent['paymentIntent'];
            if ($request->payment_gateway == 'stripe') {
                $card_token = $request->stripeToken;
                $card_number = null;
                $card_expiry = $request->expiry;
            } else if ($request->payment_gateway == 'cardknox' || $request->payment_gateway == 'cardknox_ach' || $request->payment_gateway == 'cardknox_cc') {
                $card_token = $request->xCardNum;
                $card_number = null;
                $card_expiry = $request->expiry;
            }else if($request->payment_gateway == 'banquest'){
                $card_token = null;
                $card_number = $request->card;
                $card_expiry = null;
            } else if($request->payment_gateway == 'authorize_net') {
                $card_token = $paymentDatils['cardToken'];
                $card_number = $paymentDatils['cardNumber'];
                $card_expiry = $paymentDatils['cardExpiry'];
            }else{
                $card_token = null;
                $card_number = null;
                $card_expiry = null;
            }
            $TransactionDetails = TransactionDetails::create(
                [
                    'donation_id' => $DonationsId,
                    'pay_method' => $request->payment_gateway,
                    'card_expiry' => $card_expiry,
                    'card_number'=> $card_number,
                    'card_token' => $card_token,
                    'error_detail' => '',
                ]
            );
            DB::commit();

            // return  $Donations;
            return response()->json(['Donations' => $Donations, 'DonationSTP' => $DonationSTP, 'TransactionDetails' => $TransactionDetails, 'success' => 'Donation Added'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function import(Request $request)
    {

        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'imp_don_file' => 'required|file|mimes:csv,txt',
        ]);

        $campaign_id = $request->input('campaign_id');
        $file = $request->file('imp_don_file');

        $requiredFields = [
            'First Name',
            'Donation Amount'
        ];


        $validationErrors = $this->validateCsvData($file->getPathname(), $requiredFields, $campaign_id);
        if (!empty($validationErrors)) {
            return redirect()->back()->withErrors($validationErrors)->withInput();
        }

        DB::beginTransaction();

        try {
            if (($handle = fopen($file->getPathname(), 'r')) !== FALSE) {
                $rowCounter = 0;

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $rowCounter++;
                    if ($rowCounter == 1) {
                        if ($data[0] == 'First Name' || $data[1] == 'Last Name' || $data[2] == 'Email' || $data[3] == 'Team Slug' || $data[4] == 'Phone Number' || $data[5] == 'Donation Amount') {
                            continue;
                        }
                    }

                    if (empty($data[5]) || is_null($data[5])) {
                        continue;
                    }


                    if (strpos($data[7], '$') !== false) {
                        $stpAmnt = str_replace(",", "", str_replace("$", "", $data[7]));
                    } else {
                        $stpAmnt = $data[7];
                    }

                    $manualentries =  $data[10];
                    $priceOption = "Custom Amount";
                    if ($data[9] == "") {
                        $data[9] = 0;
                    }
                    $donationData = [
                        'campaign_id' => $campaign_id,
                        'donor_first_name' => $data[0],
                        'donor_last_name' => $data[1],
                        'donor_email' => $data[2],
                        'team_slug' => $data[3],
                        'donor_phone' => $data[4],
                        'usd_amount' => str_replace(",", "", str_replace("$", "", $data[5])),
                        'amount' => str_replace(",", "", str_replace("$", "", $data[5])),
                        'currency' => 'USD',
                        'address' => $data[6] ?? '',
                        'is_anonymous' => 0,
                        'team_id' => 0,
                        'coupon_id' => $data[9] ?? 0,
                        'season_id' => $request->session()->get('season_id'),
                        'recurring' => "oneTime",
                        'price_option' => $priceOption,
                        'created_by' => auth()->id(),
                    ];


                    if ($manualentries) {
                        $donationData['entries'] = $manualentries;
                    }

                    if (!empty(trim($data[3]))) {
                        $team = Campaign::find($campaign_id)->teams()->where('slug', $data[3])->first();
                        if ($team) {
                            $donationData['team_id'] = $team->id;
                        }
                    }

                    $donation = Donations::create($donationData);

                    if ($stpAmnt) {
                        $stpEntries =  $data[8];
                        $stp = DonationSplitPot::create([
                            'donation_id' => $donation->id,
                            'campaign_split_pot_id' => $campaign_id,
                            'stp_usd_amount' => $stpAmnt,
                            'stp_amount' => $stpAmnt,
                            'stp_entries' => $stpEntries
                        ]);
                    }
                }
                fclose($handle);
            }

            DB::commit();
            return redirect()->route('admin.donations')->with('success', 'Offline donations imported successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Error importing donations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error importing donations. Please try again.' . $e->getMessage());
        }
    }

    private function validateCsvData($filePath, $requiredFields, $campaign_id)
    {

        $errors = [];

        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");

            foreach ($requiredFields as $key => $field) {
                if (!in_array($field, $header)) {
                    $errors[] = "The file is missing the required field: $field";
                }
            }

            fclose($handle);
        } else {
            $errors[] = "Unable to open the file.";
        }

        return $errors;
    }
}
