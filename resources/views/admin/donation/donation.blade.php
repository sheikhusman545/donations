@extends('admin.layout')
@section('title', 'Donations')

@section('content')
<style>
    #donationDataTable_wrapper .row .col-sm-12 {
        overflow: auto !important;
    }

    #donationDataTable_wrapper th {
        margin-right: auto !important;
        text-align: left !important;
    }

</style>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Donations</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">All Donations</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <div class="m-0">
                        <a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">

                            <i class="fas fa-filter"></i>
                            Filter</a>
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_63de8accb151e">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <form id="donationFilterForm">
                                <div class="px-7 py-5">
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Choose Campaign</label>
                                        <div>
                                            <select class="form-select form-select-solid" id="campaignFilter" name="campaign_id" data-kt-select2="true" data-placeholder="Select Campaign"  data-allow-clear="true">
                                                <option></option>
                                                @foreach ($campaigns as $campaign)
                                                <option value="{{ $campaign->id }}">{{ $campaign->camp_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div>
                                            <select name="status" id="statusFilter" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                                <option></option>
                                                <option value="Paid">Paid</option>
                                                <option value="Failed">Failed</option>
                                                <option value="Refunded">Refunded</option>
                                                <option value="Unverified">Unverified</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Type</label>
                                        <div>
                                            <select name="type" id="typeFilter" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                                <option></option>
                                                <option value="offline">Offline Donation</option>
                                                <option value="online">Online Donation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Start Date</label>
                                        <div>
                                            <input name="start_date" id="startDateFilter" type="datetime-local" class="form-control form-conrol-solid">

                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">End Date</label>
                                        <div>
                                            <input name="end_date" id="endDateFilter" type="datetime-local" class="form-control form-conrol-solid">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{-- <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button> --}}
                                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">

                            <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                            <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="fas fa-file-export"></i>
                                Export Report</button>
                            <div id="kt_ecommerce_report_customer_orders_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">Export as Excel</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0" style="overflow: auto;">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%" id="donationDataTable">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th style="white-space: nowrap" class="min-w-100px">Action</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Campaign</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Name</th>
                                    <th style="white-space: nowrap" class="min-w-100px">USD Amount</th>
                                    <th style="white-space: nowrap" class="text-end min-w-75px">Reccurring</th>
                                    <th style="white-space: nowrap" class="text-end min-w-75px">Total Donation</th>
                                    <th style="white-space: nowrap" class="text-end min-w-100px">Team</th>
                                    <th style="white-space: nowrap" class="text-end min-w-100px">Status</th>
                                    <th style="white-space: nowrap" class="text-end min-w-100px">Date</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600 donation_body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<script>
    
    $(document).ready(function() {
            var table = $('#donationDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.getDonationData') }}",
                    data: function(d) {
                        d.campaign_id = $("#campaignFilter").val();
                        d.status = $("#statusFilter").val();
                        d.type = $("#typeFilter").val();
                        d.start_date = $("#startDateFilter").val();
                        d.end_date = $("#endDateFilter").val();
                    }
                },
                columns: [
                    { data: 'action', name: 'action' },
                    { data: 'campaign_title', name: 'campaign_title' },
                    { data: 'donor_name', name: 'donor_name' },
                    { data: 'usd_amount', name: 'usd_amount' },
                    { data: 'recurring', name: 'recurring' },
                    { data: 'amount', name: 'amount' },
                    { data: 'team_name', name: 'team_name' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                ]
            });

            $('#donationFilterForm').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });

</script>
@endsection
