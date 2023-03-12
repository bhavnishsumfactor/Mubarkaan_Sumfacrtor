<template>
  <header class="account-area_head">
     <a
        class="hemburger"
        href="javascript:;"
        data-trigger="account-menu"
      >
        <svg class="svg">
          <use
            :xlink:href="
              baseUrl + '/dashboard/media/retina/sprite.svg#hemburger'
            "
          ></use>
        </svg>
      </a>
    <div class="logo">
      <a :href="baseUrl">
        <img
          id="yk-header-logo"
          :data-lite="
            baseUrl + '/' + this.$getFileUrl('frontendLogo', 0, 0, 'thumb')
          "
          :data-dark="
            baseUrl +
              '/' +
              this.$getFileUrl('frontendDarkModeLogo', 0, 0, 'thumb')
          "
          :src="baseUrl + '/' + this.$getFileUrl('frontendLogo', 0, 0, 'thumb')"
          alt=""
          :data-ratio="this.$configVal('FRONTEND_LOGO_RATIO')"
        />
      </a>
    </div>
    <div class="quick">      
      <div class="user-card"  v-if="$mq !== 'mobile' && $mq !== 'tablet'">
        <div class="user-card_inner">
          <span class="user-card_photo">
            <img class="YK-userAvatar" :src="profileImage" alt="" />
          </span>
          <div clss="">
            <h4 class="user-card_name">
              <strong> Hi</strong> <br>
              {{ auth.user_fname + " " + auth.user_lname }}
            </h4>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
<script>
export default {
  data: function() {
    return {
	  baseUrl: baseUrl,
	  auth: window.auth,
	   profileImage: "",
    };
  },
  methods: {
    switchLanguage: function(language) {
      this.$axios
        .post(baseUrl + "/user/switch-language", { language: language })
        .then((response) => {          
          if (response.data.status == true) {
            window.location.href =
              window.location.href +
              "?language=" +
              language +
              "#googtrans(auto|" +
              language +
              ")";
          }
        });
      //window.location.href = window.location.href + '?language=' + language + '#googtrans(auto|'+ language +')';
    },
    
  },
  
    mounted: function() {
        this.profileImage = this.baseUrl + "/" + this.$getFileUrl("userProfileImage", auth.user_id, 0, "50-50", Date.now());        
        this.$root.$on("updateProfileImage", (data) => {
            this.profileImage = data;
        });
        this.languages = window.languages;
        this.currencies = window.currencies;
        this.selectedLanguage = window.selectedLanguage;
        this.selectedCurrency = window.selectedCurrency;
        this.defaultFlag = window.defaultFlag;
        $('body').find('*[data-trigger]').click(function () {

        var targetElmId = $(this).data('trigger');
        var elmToggleClass = targetElmId + '--on';
        if ($('body').hasClass(elmToggleClass)) {
            $('body').removeClass(elmToggleClass);
        } else {
            $('body').addClass(elmToggleClass);
        }
        
    });
    
    if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
      if ($.cookie(systemPrefix + 'ThemeStyle') == 'light') {
          $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-lite'));
      } else {
          if ($("#yk-header-logo").attr('data-dark') != "") {
              $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-dark'));
          }
      }
    }

$('body').find('*[data-target-close]').click(function () {
	var targetElmId = $(this).data('target-close');
	$('body').toggleClass(targetElmId + '--on');
});

$('body').mouseup(function (event) {

	if ($(event.target).data('trigger') != '' && typeof $(event.target).data('trigger') !== typeof undefined) {
		event.preventDefault();
		return;
	}

	$('body').find('*[data-close-on-click-outside]').each(function (idx, elm) {
		var slctr = $(elm);
		if (!slctr.is(event.target) && !$.contains(slctr[0], event.target)) {
			$('body').removeClass(slctr.data('close-on-click-outside') + '--on');
		}
	});
});
$('.menu__link').click(function () {
  	$('body').removeClass('account-menu--on');
  });
  },
};
</script>
