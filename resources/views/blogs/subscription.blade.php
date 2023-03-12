<section class="section section-subscribe">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-md-7">
                <div class="subscribe-wrapper">
                    <div class="subscribe-icon mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44.178" height="35" viewBox="0 0 44.178 35">
                            <defs>
                                <style>
                                    .a {
                                        fill: none;
                                        stroke: #e72548;
                                        stroke-linecap: round;
                                        stroke-linejoin: round;
                                        stroke-width: 3px;
                                    }
                                </style>
                            </defs>
                            <g transform="translate(-0.911 -4.5)">
                                <path class="a" d="M7,6H39a4.012,4.012,0,0,1,4,4V34a4.012,4.012,0,0,1-4,4H7a4.012,4.012,0,0,1-4-4V10A4.012,4.012,0,0,1,7,6Z"></path>
                                <path class="a" d="M43,9,23,23,3,9" transform="translate(0 1)"></path>
                            </g>
                        </svg>
                    </div>
                    <h3>{{__("LBL_SUBSCRIBE_TO_NEWSLETTER")}}</h3>
                    <p>{{__("LBL_BE_FIRST_TO_KNOW_ABOUT")}}.</p>
                    <form method="POST" action="{{route('newsletterPost')}}" class="yk-newsletter">
                        <input type="email" name="email" placeholder="{{__('PLH_ENTER_YOUR_EMAIL_ADDRESS')}}">
                        <button class="btn btn-submit btn-block YK-subscribeNewsletter" type="submit" name="subscribe" value="Submit"><i class="fa fa-arrow-right ml-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>