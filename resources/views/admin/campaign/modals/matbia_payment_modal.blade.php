{{-- MATBIA MODAL --}}
<div class="modal fade" id="matbia_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>Matbia Configurations</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">

                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y m-3">
                <form action="">
                    <div class="card-body pt-">

                        <div class="w-100">
                            <h3>Test Account</h3>
                            <div class="fv-row mb-10">
                                <label class="form-label ">API Token</label>
                                <input name="matbia_test_api_token" class="form-control form-control-lg form-control-solid" value="" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Tax ID</span>

                                </label>
                                <input name="matbia_test_org_tax_id" class="form-control form-control-lg form-control-solid" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Email</span>

                                </label>
                                <input name="matbia_test_org_email" class="form-control form-control-lg form-control-solid" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Name</span>

                                </label>
                                <input name="matbia_test_org_name" class="form-control form-control-lg form-control-solid" />
                            </div>

                        </div>

                        <div class="w-100">
                            <h3>Live Account</h3>
                            <div class="fv-row mb-10">
                                <label class="form-label ">API Token</label>
                                <input name="matbia_live_api_token" class="form-control form-control-lg form-control-solid" value="" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Tax ID</span>

                                </label>
                                <input name="matbia_live_org_tax_id" class="form-control form-control-lg form-control-solid" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Email</span>

                                </label>
                                <input name="matbia_live_org_email" class="form-control form-control-lg form-control-solid" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center form-label">
                                    <span class="">Org. Name</span>

                                </label>
                                <input name="matbia_live_org_name" class="form-control form-control-lg form-control-solid" />
                            </div>

                        </div>


                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="buttom" class="btn btn-lg btn-primary" data-kt-element="type-next">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
            <!--begin::Modal body-->
        </div>
    </div>
</div>
