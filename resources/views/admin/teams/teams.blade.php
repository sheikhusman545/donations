@extends('admin.layout')
@section('title', 'Teams')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $campaign->camp_title }} Teams</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Teams</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <div class="m-0">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#add_team_modal" class="btn btn-sm btn-flex btn-info"> <i class="fas fa-user"></i> Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_contentss" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header p-4">
                        <div class="d-flex w-100 row">
                            <div class="col-md-6">
                                <h3>
                                    Bulk Import Offline Teams
                                </h3>
                            </div>
                            <div class="col-md-6 text-end">

                                <a href="#" class="ms-end btn btn-sm btn-primary">Download CSV Template</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form action="" method="post">
                            <div class="row w-100">
                                <div class="col-md-12 col-12">
                                    <div class="mb-10 fv-row">
                                        <label class="required form-label mb-3">CSV Donation File</label>
                                        <input type="file" class="form-control form-control-lg form-control-solid" value="{{ old('csv_template') }}" name="csv_template" />
                                        @if ($errors->has('csv_template'))
                                        <div class="text-danger">{{ $errors->first('csv_template') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-lg btn-primary me-3">
                                        <span class="indicator-label">Submit
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-flush mt-4">
                    <div class="card-header pt-4">
                        <h3 class="text-start">
                            Teams
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="coupons_datatable">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th style="white-space: nowrap" class="min-w-100px">First name</th>
                                    <th style="white-space: nowrap" class="min-w-100px"> Last name</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Display name</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Email</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Team Slug</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Phone System Code</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Team Goal</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Currency</th>
                                    <th style="white-space: nowrap" class="min-w-100px">Action</th>

                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600 coupons_body">
                                @foreach ($teams as $team)
                                <tr>
                                    <td>{{ $team->first_name }}</td>
                                    <td>{{ $team->last_name }}</td>
                                    <td>{{ $team->display_name }}</td>
                                    <td>{{ $team->email }}</td>
                                    <td>{{ $team->slug }}</td>
                                    <td>{{ $team->phone_system_code }}</td>
                                    <td>{{ $team->goal }}</td>
                                    <td>{{ $team->currency }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i> </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" data-route="{{ route('admin.getSingleTeamData') }}" data-id="{{ $team->id }}" id="update_team{{$team->id}}" class="loadEditTeamModal menu-link px-3">
                                                    Edit
                                                </a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.team.destroy', ['teamId' => $team->id]) }}"  class="menu-link px-3">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

@include('admin.teams.modals.AddteamsModal')
@include('admin.teams.modals.EditTeamsModal')

<script>
    $('.loadEditTeamModal').on('click', function() {
        let teamId = $(this).data('id')
        // alert(ticketOptionId)
        let action = $(this).data('route')
        let data = {
            'teamId': teamId
            , '_token': '{{ csrf_token() }}'
        }
        ajaxCall(action, 'POST', data, function(response) {

                let team = response.teams;
                if (team) {
                    $('#teamId').val(team.id);
                    $('#campaign_id').val(team.campaign_id);
                    $('#EditTeamFirstName').val(team.first_name);
                    $('#EditTeamLastName').val(team.last_name);
                    $('#EditTeamDisplayName').val(team.display_name);
                    $('#EditTeamEmail').val(team.email);
                    $('#EditTeamSlug').val(team.slug);
                    $('#EditTeamCurrency').val(team.currency);
                    if(team.currency == 'cad'){
                        $('#editCad').attr('selected','selected')
                    }else if(team.currency == 'usd'){
                        $('#editUsd').attr('selected','selected')
                    }
                    $('#EditTeamGoal').val(team.goal);
                    $('#EditTeamPhoneSystemCode').val(team.phone_system_code);

                } else {
                    console.error("Team not found in response", response);
                }

                $('#update_team_modal').modal('show');
            }
            , function(xhr, status, error) {
                console.error('Error:', error);

                swal.fire({
                    text: 'Error:' + error
                    , icon: "error"
                    , buttonsStyling: false
                    , confirmButtonText: "Ok, got it!"
                    , customClass: {
                        confirmButton: "btn btn-light-primary"
                    }
                });
            }
        );
    })

</script>

@endsection
