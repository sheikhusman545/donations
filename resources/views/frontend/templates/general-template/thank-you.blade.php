@section('title', 'Thank You')
{{-- @section('raffle_template') --}}
@include('frontend.templates.general-template.includes.header')

<section class="section_gap thank_top_section">
    <div class="container-fluid p-0 m-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="main_heading">
                        <h1>
                            THANK YOU FOR <br>
                            YOUR <span>DONATION</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section_gap thank_main_section">
    <div class="container-fluid ">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mt-3 mb-3">
                      <div class="heading">
                        <h2>
                            DEAR <span> SOLOMON VEGH,</span>
                        </h2>
                        <p>
                            Thank you for your generous donation of <span>$18.00</span>
                        </p>
                        <p>
                            An official receipt will be sent to <span>billing@cornerstonebld.com</span>
                        </p>
                        <hr>
                      </div>
                      <div class="donor_information">
                        <div class="heading">
                           <h3>
                            Donor Information
                           </h3>
                        </div>
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td>Solomon Vegh</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>billing@cornerstonebld.com</td>
                            </tr>
                            <tr>
                                <td>Phone:</td>
                                <td>+9234172741</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>
                                    12 Craftwood Dr, Spring Valley,
                                    New York, 10977,
                                    United States</td>
                            </tr>
                        </table>
                      </div>
                </div>
                <div class="col-lg-5 mt-3 mb-3">
                    <div class="donation_ticket_details">
                        <div class="heading">
                           <h3>
                            Donation & Ticket Details
                           </h3>
                        </div>
                        <table>
                            <tr>
                                <td>Donation Date:</td>
                                <td>June 17, 2024, 10:12 am</td>
                            </tr>
                            <tr>
                                <td>Donation Amount:</td>
                                <td>$18.00</td>
                            </tr>
                            <tr>
                                <td>Payment Method:</td>
                                <td>Credit Card <i>(ending in 3478)</i> </td>
                            </tr>
                            <tr>
                                <td>Team:</td>
                                <td>Ruchie Perlstein</td>
                            </tr>
                            <tr>
                                <td>Split the Pot:</td>
                                <td>1 entry for $18</td>
                            </tr>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('frontend.templates.general-template.includes.footer')
