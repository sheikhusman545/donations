@extends('admin.layout')
@section('title', 'Donation Details')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Donation Details</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Donations Details</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <div class="m-0">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#donation_change_team_modal" class="btn btn-sm btn-flex btn-info"> <i class="fas fa-user"></i> Change Team</button>
                    </div>
                    <div class="m-0">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#edit_donation_modal" class="btn btn-sm btn-flex btn-success"> <i class="fas fa-edit"></i> Edit</button>
                    </div>
                    <div class="m-0">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#donation_refund_modal" class="btn btn-sm btn-flex btn-danger"> <i class="fas fa-exchange"></i> Refund</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                    <li class="breadcrumb-item text-muted">
                                        <h3>Status</h3>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                    </li>
                                    @if ($details->status == 'Refunded')
                                    <li class="breadcrumb-item text-muted">
                                        <h3 style="font-size: 17px;" class="badge badge-light-info">Refunded</h3>
                                    </li>
                                    @elseif ($details->status == "Paid")
                                    <li class="breadcrumb-item text-muted">
                                        <h3 style="font-size: 17px;" class="badge badge-light-success">Paid</h3>
                                    </li>
                                    @elseif ($details->status == "Unverified")
                                    <li class="breadcrumb-item text-muted">
                                        <h3 style="font-size: 17px;" class="badge badge-light-warning">Unverified</h3>
                                    </li>
                                    @elseif ($details->status == "Failed")
                                     <li class="breadcrumb-item text-muted">
                                        <h3 style="font-size: 17px;" class="badge badge-light-danger">Failed</h3>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <tbody class="fw-semibold text-gray-600 donation_body">
                                @if ($details->status == 'Refunded')
                                <tr class="bg-info bg-gradient text-white">
                                    <td class="px-3"><b>Refund Status</b></td>
                                    <td class="text-start">Refund Approved</td>
                                </tr>
                                <tr class="bg-info  text-white">
                                    <td class="px-3"><b>Schedule Status</b></td>
                                    <td class="text-start">Schedule Disabled</td>
                                </tr>
                                <tr class="bg-info text-white">
                                    <td class="px-3"><b>Refund Date</b></td>
                                    <td class="text-start"> 04/11/2024 - 09:28 PM</td>
                                </tr>
                                <tr class="bg-info text-white">
                                    <td class="px-3"><b>Refund Notes</b></td>
                                    <td class="text-start">Donated refunded</td>
                                </tr>
                                <tr>
                                    <td><b>Donation ID</b></td>
                                    <td class="text-start">{{ $details->id }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><b>Transaction ID</b></td>
                                    <td class="text-start">@if (isset($details->transaction_id))
                                        {{ $details->transaction_id }}
                                        @endif</td>
                                </tr>
                                <tr>
                                    <td><b>Team</b></td>
                                    <td class="text-start">Ruchy Follman</td>
                                </tr>
                                <tr>
                                    <td><b>Donor Name</b></td>
                                    <td class="text-start">
                                        @if (isset($details->donor_first_name) && isset($details->donor_last_name))
                                        {{ $details->donor_first_name . ' ' . $details->donor_last_name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Email</b></td>
                                    <td class="text-start">
                                        @if (isset($details->donor_email))
                                        {{ $details->donor_email }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Phone</b></td>
                                    <td class="text-start">
                                        @if (isset($details->donor_phone))
                                        {{ $details->donor_phone }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Address</b></td>
                                    <td class="text-start">
                                        @if (isset($details->address))
                                        {{ $details->address }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Street</b></td>
                                    <td class="text-start">
                                        @if (isset($details->street))
                                        {{ $details->street }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor City</b></td>
                                    <td class="text-start">
                                        @if (isset($details->city))
                                        {{ $details->city }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor State</b></td>
                                    <td class="text-start">
                                        @if (isset($details->state))
                                        {{ $details->state }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Zipcode</b></td>
                                    <td class="text-start">
                                        @if (isset($details->zipcode))
                                        {{ $details->zipcode }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Donor Country</b></td>
                                    <td class="text-start">
                                        @if (isset($details->country))
                                        {{ $details->country }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>Donor Neighborhood</b></td>
                                    <td class="text-start">
                                        @if (isset($details->neighborhood))
                                        {{ $details->neighborhood }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>Package</b></td>
                                    <td class="text-start">

                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Ticket/s</b></td>
                                    <td class="text-start">
                                        @if (isset($details->entries))
                                        {{ $details->entries }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Comments</b></td>
                                    <td class="text-start">
                                        @if (isset($details->comments))
                                        {{ $details->comments }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Paid By</b></td>
                                    <td class="text-start">
                                        @if (isset($details->paid_by))
                                        {{ $details->paid_by }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Campaign Name</b></td>
                                    <td class="text-start">
                                        @if (isset($campaign->camp_title))
                                        {{ $campaign->camp_title }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Amount</b></td>
                                    <td class="text-start">
                                        @if (isset($details->amount))
                                       ${{ $details->amount }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Transaction Fee</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Donation Gifts</b></td>
                                    <td class="text-start">
                                        <ul>
                                            {{-- <li>This is gift</li> --}}
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Coupon Applied</b></td>
                                    <td class="text-start">
                                        @if (isset($details->coupon_id))
                                        {{ $details->coupon_id }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Recurring</b></td>
                                    <td class="text-start">
                                        @if (isset($details->recurring))
                                        ${{ $details->recurring }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Total Donation</b></td>
                                    <td class="text-start">
                                        @if (isset($details->usd_amount))
                                        ${{ $details->usd_amount }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Payment Method</b></td>
                                    <td class="text-start">
                                        @if (isset($details->payment_gateway))
                                        {{ $details->payment_gateway }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Transaction Card Number</b></td>
                                    <td class="text-start">
                                        @if (isset($details->custom_transaction_number))
                                        ${{ $details->custom_transaction_number }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Transaction Card Type</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Transaction Card Expiry (mm/yy)</b></td>
                                    <td class="text-start"></td>
                                </tr>

                                <tr>
                                    <td><b>Payment Reference ID</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Referrer Name</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Referrer Link</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Page Link</b></td>
                                    <td class="text-start">
                                        <a href="#"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="text-danger">Transaction Error Detail</b></td>
                                    <td class="text-start text-danger"></td>
                                </tr>
                                <tr>
                                    <td><b>Visitor ID</b></td>
                                    <td class="text-start"></td>
                                </tr>
                                <tr>
                                    <td><b>Notes</b><button  type="button" data-bs-toggle="modal" data-bs-target="#donation_notes_modal" class="getDonationNotes ms-3 btn btn-dark"><i class="fas fa-edit"></i>Edit</button></td>
                                    <td class="text-start">
                                        @if (isset($details->custom_transaction_number))
                                        {{ $details->custom_transaction_number }}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Date</b></td>
                                    <td class="text-start">
                                        @if (isset($details->created_at))
                                        {{ $details->created_at }}
                                         @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_contentss" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header pt-4">
                        <h3 class="text-start">
                            Recurring Details
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            {{-- <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px"></th>
                                    <th class="min-w-100px"></th>

                                </tr>
                            </thead> --}}
                            <tbody class="fw-semibold text-gray-600 donation_body">

                                <tr>
                                    <td><b>
                                            Start Date</b></td>
                                    <td class="text-start">04/11/2024</td>
                                </tr>
                                <tr>
                                    <td><b>Upcoming Payment Date</b></td>
                                    <td class="text-start"> 04/11/2024 - 09:28 PM</td>
                                </tr>
                                <tr>
                                    <td><b>Payment Per Cycle</b></td>
                                    <td class="text-start">$25.00</td>
                                </tr>
                                <tr>
                                    <td><b>Interval</b></td>
                                    <td class="text-start">Monthly</td>
                                </tr>
                                <tr>
                                    <td><b>No. of Payments Left</b></td>
                                    <td class="text-start">3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('admin.donation.modals.donation_refund_modal')
@include('admin.donation.modals.donation_notes')
@include('admin.donation.modals.edit_donation_modal')
@include('admin.donation.modals.change_team_modal')

@endsection
