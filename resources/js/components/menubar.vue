<template>
    <div class="stellarnav" v-bind:class="[ isTablet === true ? 'mobile' : '']">
      <ul class="Yk-routing">
        <li class="yk-routing-li">
          <router-link :to="{ name: 'dashboard' }">{{
            $t("LNK_DASHBOARD")
          }}</router-link>
        </li>
        <li
          v-if="
            $canRead('BRANDS') ||
            $canRead('CATEGORIES') ||
            $canRead('OPTIONS') ||
            $canRead('PRODUCTS') ||
            $canRead('PRODUCT_REVIEWS')
          "
        >
          <a href="javascript:;">{{ $t("LNK_PRODUCTS") }}</a>
          <ul>
            <li v-if="$canRead('BRANDS')">
              <router-link :to="{ name: 'brands' }">{{
                $t("LNK_BRANDS")
              }}</router-link>
            </li>
            <li v-if="$canRead('CATEGORIES')">
              <router-link :to="{ name: 'productCategory' }">{{
                $t("LNK_CATEGORIES")
              }}</router-link>
            </li>
            <li v-if="$canRead('OPTIONS')">
              <router-link :to="{ name: 'options' }">{{
                $t("LNK_OPTION_GROUPS")
              }}</router-link>
            </li>
            <li v-if="$canRead('PRODUCTS')">
              <router-link :to="{ name: 'products' }">{{
                $t("LNK_PRODUCTS")
              }}</router-link>
            </li>
            <li v-if="$canRead('PRODUCT_REVIEWS')">
              <router-link :to="{ name: 'productReviewsListing' }">{{
                $t("LNK_PRODUCT_REVIEWS")
              }}</router-link>
            </li>
          </ul>
        </li>
        <li
          class="yk-routing-li"
          v-if="
            $canRead('SPECIAL_PRICES') ||
            $canRead('DISCOUNT_COUPONS') ||
            $canRead('BUY_TOGETHER_PRODUCTS') ||
            $canRead('RELATED_PRODUCTS') ||
            $canRead('REWARDS_POINTS')
          "
        >
          <a href="javascript:;">{{ $t("LNK_PROMOTIONS") }}</a>
          <ul>
            <li v-if="$canRead('SPECIAL_PRICES')">
              <router-link :to="{ name: 'specialPrices' }">{{
                $t("LNK_SPECIAL_PRICES")
              }}</router-link>
            </li>
            <li v-if="$canRead('DISCOUNT_COUPONS')">
              <router-link :to="{ name: 'discountCoupons' }">{{
                $t("LNK_DISCOUNT_COUPONS")
              }}</router-link>
            </li>
            <li v-if="$canRead('BUY_TOGETHER_PRODUCTS')">
              <router-link :to="{ name: 'buyTogetherProducts' }">{{
                $t("LNK_BUY_TOGETHER_PRODUCTS")
              }}</router-link>
            </li>
            <li v-if="$canRead('RELATED_PRODUCTS')">
              <router-link :to="{ name: 'relatedProducts' }">{{
                $t("LNK_RELATED_PRODUCTS")
              }}</router-link>
            </li>
            <li v-if="$canRead('REWARDS_POINTS')">
              <router-link :to="{ name: 'rewardPoints' }">{{
                $t("LNK_REWARD_POINTS")
              }}</router-link>
            </li>
          </ul>
        </li>
        <li
          class="yk-routing-li"
          v-if="$canRead('ORDERS') || $canRead('ADD_ORDERS')"
        >
          <router-link :to="{ name: 'orders' }">{{
            $t("LNK_ORDERS")
          }}</router-link>
        </li>
        <li
          class="yk-routing-li"
          v-if="
            $canRead('PAGES') ||
            $canRead('BLOGS') ||
            $canRead('FAQ') ||
            $canRead('TESTIMONIALS') ||
            $canRead('EMAIL_TEMPLATES') ||
            $canRead('SMS_TEMPLATES') ||
            $canRead('NOTIFICATION_TEMPLATES') ||
            $canRead('LANGUAGE_LABELS')
          "
        >
          <a href="javascript:;">{{ $t("LNK_CMS") }}</a>
          <ul>
            <li v-if="$canRead('PAGES')">
              <router-link :to="{ name: 'pages' }">{{
                $t("LNK_PAGES")
              }}</router-link>
            </li>
            <li v-if="$canRead('BLOGS')">
              <a href="javascript:;">{{ $t("LNK_BLOGS") }}</a>
              <ul>
                <li>
                  <router-link :to="{ name: 'blogCategory' }">{{
                    $t("LNK_CATEGORIES")
                  }}</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'blogs' }">{{
                    $t("LNK_BLOG_ARTICLES")
                  }}</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'blogComment' }">{{
                    $t("LNK_BLOG_COMMENTS")
                  }}</router-link>
                </li>
              </ul>
            </li>
            <li v-if="$canRead('FAQ')">
              <router-link :to="{ name: 'faqs' }">{{
                $t("LNK_FAQS")
              }}</router-link>
            </li>
            <li v-if="$canRead('TESTIMONIALS')">
              <router-link :to="{ name: 'testimonials' }">{{
                $t("LNK_TESTIMONIALS")
              }}</router-link>
            </li>
            <li v-if="$canRead('EMAIL_TEMPLATES')">
              <router-link :to="{ name: 'emailTemplates' }">{{
                $t("LNK_EMAIL_TEMPLATES")
              }}</router-link>
            </li>
            <li v-if="$canRead('SMS_TEMPLATES')">
              <router-link :to="{ name: 'smsTemplates' }">{{
                $t("LNK_SMS_TEMPLATES")
              }}</router-link>
            </li>
            <li v-if="$canRead('NOTIFICATION_TEMPLATES')">
              <router-link :to="{ name: 'notificationTemplates' }">{{
                $t("LNK_NOTIFICATION_TEMPLATES")
              }}</router-link>
            </li>
            <li v-if="$canRead('LANGUAGE_LABELS')">
              <a href="javascript:;">{{ $t("LNK_LANGUAGE_LABELS") }}</a>
              <ul>
                <li>
                  <router-link
                    :to="{ name: 'languageLabels', params: { type: 'admin' } }"
                    >{{ $t("LNK_ADMIN") }}</router-link
                  >
                </li>
                <li>
                  <router-link
                    :to="{
                      name: 'languageLabels',
                      params: { type: 'frontend' },
                    }"
                    >{{ $t("LNK_FRONTEND") }}</router-link
                  >
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li
          class="yk-routing-li"
          v-if="
            $canRead('META_TAGS') ||
            $canRead('URL_REWRITE') ||
            $canRead('ALT_IMAGES') ||
            $canRead('MISCELLANEOUS')
          "
        >
          <a href="javascript:;">{{ $t("LNK_SEO") }}</a>
          <ul>
            <li v-if="$canRead('META_TAGS')">
              <router-link :to="{ name: 'metaTags' }">{{
                $t("LNK_META_TAGS")
              }}</router-link>
            </li>
            <li v-if="$canRead('URL_REWRITE')">
              <router-link :to="{ name: 'urlRewrite' }">{{
                $t("LNK_URL_REWRITE")
              }}</router-link>
            </li>
            <li v-if="$canRead('ALT_IMAGES')">
              <router-link :to="{ name: 'imageAttributes' }">{{
                $t("LNK_ALT_IMAGES")
              }}</router-link>
            </li>
            <li v-if="$canRead('MISCELLANEOUS')">
              <router-link :to="{ name: 'miscellaneous' }">{{
                $t("LNK_MISCELLANEOUS")
              }}</router-link>
            </li>
          </ul>
        </li>
        <li
          class="yk-routing-li"
          v-if="
            $canRead('ADMIN_USERS') ||
            $canRead('USERS') ||
            $canRead('GDPR_REQUEST')
          "
        >
          <a href="javascript:;">{{ $t("LNK_USERS") }}</a>
          <ul>
            <li v-if="$canRead('ADMIN_USERS')">
              <a href="javascript:;">{{ $t("LNK_ADMIN_USERS") }}</a>
              <ul v-if="$canRead('ADMIN_USERS')">
                <li>
                  <router-link :to="{ name: 'subAdmins' }">{{
                    $t("LNK_USERS")
                  }}</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'roles' }">{{
                    $t("LNK_ROLES")
                  }}</router-link>
                </li>
              </ul>
            </li>
            <li v-if="$canRead('USERS')">
              <a href="javascript:;">{{ $t("LNK_CUSTOMERS") }}</a>
              <ul v-if="$canRead('USERS')">
                <li>
                  <router-link :to="{ name: 'users' }">{{
                    $t("LNK_USERS")
                  }}</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'userGroups' }">{{
                    $t("LNK_GROUPS")
                  }}</router-link>
                </li>
              </ul>
            </li>
            <li v-if="$canRead('GDPR_REQUEST')">
              <router-link :to="{ name: 'userRequests' }">{{
                $t("LNK_GDPR_REQUESTS")
              }}</router-link>
            </li>
          </ul>
        </li>
        <li class="yk-routing-li">
          <a
            href="javascript:;"
            v-if="
              $canRead('SALE_REPORT') ||
              $canRead('PROFIT_MARGIN_REPORT') ||
              $canRead('ACQUISITION_REPORT') ||
              $canRead('BEHAVIOUR_REPORT') ||
              $canRead('CUSTOMER_REPORT')
            "
            >{{ $t("LNK_REPORTS") }}</a
          >
          <ul>
            <li v-if="$canRead('SALE_REPORT')">
              <router-link :to="{ name: 'saleReport' }">{{
                $t("LNK_SALES")
              }}</router-link>
            </li>
            <li v-if="$canRead('PROFIT_MARGIN_REPORT')">
              <router-link :to="{ name: 'profitReport' }">{{
                $t("LNK_PROFIT_MARGIN")
              }}</router-link>
            </li>
            <li v-if="$canRead('ACQUISITION_REPORT')">
              <router-link :to="{ name: 'acquisitionReport' }">{{
                $t("LNK_ACQUISITIONS")
              }}</router-link>
            </li>
            <li v-if="$canRead('BEHAVIOUR_REPORT')">
              <router-link :to="{ name: 'behaviorReport' }">{{
                $t("LNK_BEHAVIOUR")
              }}</router-link>
            </li>
            <li v-if="$canRead('CUSTOMER_REPORT')">
              <router-link :to="{ name: 'customerReport' }">{{
                $t("LNK_CUSTOMERS")
              }}</router-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
</template>
<script>
export default {
props:['isTablet'],
  mounted() {
    let thisObj = this;
    $(document).ready(function () {
      if (
        $("ul.Yk-routing > li").length == 2 &&
        $("ul.Yk-routing > li").hasClass("has-sub")
      ) {
        var html = $(".Yk-routing").find("ul").html();
        $(".Yk-routing").find("li.has-sub").remove();
        $(".Yk-routing").append(html);
        $(document).on("mouseover", ".has-sub", function () {
          $(this).children("ul").show();
        });
        $(document).on("mouseout", ".has-sub", function () {
          $(this).children("ul").hide();
        });
      }
      window.addEventListener("keydown", function (e) {
        if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
          if (thisObj.searchEnabled) {
            e.preventDefault();
            thisObj.openModal();
          }
        }
      });
    });
  },
};
</script>
