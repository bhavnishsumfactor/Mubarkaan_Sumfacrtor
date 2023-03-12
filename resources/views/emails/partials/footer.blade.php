</table>
</td>
</tr>
<tr>
    <td style="padding-top:30px;">
        <table width="600px" cellspacing="0" cellpadding="0" style="margin:0 auto;table-layout:fixed;">
            <tr>
                <td style="text-align:center;">
                    {!!$footer!!}

                    <h5 style="font-size: 18px;font-weight: 600;text-transform: uppercase;letter-spacing: -0.2px;line-height: 24px;
                                display: block;margin: 0 0 15px 0;color:#212529;">Get In Touch</h5>
                    @if(!empty($social['EMAIL_SOCIAL_LINKS_FACEBOOK']))
                    <a href="{{$social['EMAIL_SOCIAL_LINKS_FACEBOOK']}}" style="display: inline-block;margin: 0 4px;">
                        <img src="{{ asset('socialLogo/facebook-icon.png')}}" alt="facebook">
                    </a>
                    @endif
                    @if(!empty($social['EMAIL_SOCIAL_LINKS_TWITTER']))
                    <a href="{{$social['EMAIL_SOCIAL_LINKS_TWITTER']}}" style="display: inline-block;margin: 0 4px;">
                        <img src="{{ asset('socialLogo/twitter.png')}}" alt="twitter">
                    </a>
                    @endif
                    @if(!empty($social['EMAIL_SOCIAL_LINKS_INSTAGRAM']))
                    <a href="{{$social['EMAIL_SOCIAL_LINKS_INSTAGRAM']}}" style="display: inline-block;margin: 0 4px;">
                        <img src="{{ asset('socialLogo/instagram.png')}}" alt="instagram">
                    </a>
                    @endif
                    @if(!empty($social['EMAIL_SOCIAL_LINKS_YOUTUBE']))
                    <a href="{{$social['EMAIL_SOCIAL_LINKS_YOUTUBE']}}" style="display: inline-block;margin: 0 4px;">
                        <img src="{{ asset('socialLogo/youtube.png')}}" alt="youtube">
                    </a>
                    @endif
                    @if(!empty($social['EMAIL_SOCIAL_LINKS_PINTEREST']))
                    <a href="{{$social['EMAIL_SOCIAL_LINKS_PINTEREST']}}" style="display: inline-block;margin: 0 4px;">
                        <img src="{{ asset('socialLogo/pintrst.png')}}" alt="pinterest">
                    </a>
                    @endif
                    <span style="display: block; width: 100%; font-size: 12px; padding: 10px 0;color: #212529;"></span>

                    {!!$footer2!!}
                    @if(isset($unsubscribeToken))
                    <a href="{{url('') . '/newsletter/unsubscribe/' . $unsubscribeToken}}"
                        style="display:inline-block;font-size:14px;font-weight:500;color:212529;text-decoration:underline;border-left:1px solid #212529;line-height:12px;padding:0 15px;"
                        target="_blank">Unsubscribe</a>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="padding:40px;"></td>
</tr>
</table>
</body>

</html>