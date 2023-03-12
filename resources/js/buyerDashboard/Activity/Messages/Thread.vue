<template>
  <div class="message scroll-y" id="YK-message-container">
    <div class="message__head">
      <div class="message__title">{{ messagesDetails.thread_subject }}</div>
      <div class="message-product d-flex align-items-center ml-auto">
        <div class="message-product__thumb">
          <a
            v-if="messagesDetails.product"
            :href="$generateUrl(messagesDetails.url_rewrite)"
            v-b-tooltip.hover
            :title="messagesDetails.product.prod_name"
          >
            <img
              data-yk=""
              :src="
                $productImage(
                  messagesDetails.thread_product_id,
                  messagesDetails.thread_product_variant,
                  messagesDetails.images ? messagesDetails.images.afile_updated_at : '',
                  '38-40',
                )
              "
            />
          </a>
        </div>
        <button
          class="btn btn-brand gb-btn gb-btn-primary"
          name="YK-addToCartBtn"
          v-b-tooltip.hover
          title="Add to cart"
          type="button"
          @click="
            messagesDetails.available != false
              ? $addToCart(
                  messagesDetails.thread_product_id,
                  messagesDetails.thread_product_variant,
                )
              : ''
          "
          v-if="messagesDetails.available != false"
        >
          <span class="icon">
            <svg class="svg">
              <use
                :xlink:href="
                  baseUrl + '/dashboard/media/retina/sprite.svg#cart'
                "
                :href="baseUrl + '/dashboard/media/retina/sprite.svg#cart'"
              ></use>
            </svg>
          </span>
        </button>
      </div>
    </div>

    <div class="message__body">
      <div class="widget widget--messages messages">
        <ul id="YK-chat-messages">
          <thread-messages
            v-for="(message, mKey) in messagesDetails.thread_messages"
            :key="mKey"
            :message="message"
          ></thread-messages>
          <li class="message__chat message--right" v-if="uploadImage == true">
            

            <div class="msg">
              
              <div class="message__wrapper">
                <div class="message__text">
                  <p>
                     <img
                    data-yk=""
                    :src="
                      baseUrl + '/dashboard/media/retina/loadingchatimage.svg'
                    "
                    alt=""
                  />
                  </p>
                </div>
             
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="message__foot">
      <div class="message__input">
        <div class="message__editor">
          <textarea
            class="form-control"
            id="txtmessage"
            v-model="userData.txtMessage"
            @keydown.enter.exact.prevent
            @keyup.enter.exact="sendMessage"
            @keydown.enter.shift.exact="newline"
            style="height: 40px;"
            :placeholder="$t('PLH_TYPE_HERE') + '...'"
          ></textarea>
          <div class="message__emoji">
            <twemoji-picker
              :emojiData="emojiDataAll"
              :emojiGroups="emojiGroups"
              :pickerWidth="300"
              :pickerHeight="200"
              :pickerCloseOnClickaway="true"
              @emojiUnicodeAdded="selectEmoji"
            >
              <template v-slot:twemoji-picker-button>
                <button class="btn btn-lg emoji-icn">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    height="16"
                    version="1.1"
                    id="Capa_1"
                    x="0px"
                    y="0px"
                    viewBox="0 0 512 512"
                    style="enable-background: new 0 0 512 512;"
                    xml:space="preserve"
                  >
                    <g>
                      <g>
                        <path
                          d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256    s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98    C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40    s216,96.897,216,216S375.103,472,256,472z"
                        />
                      </g>
                    </g>
                    <g>
                      <g>
                        <path
                          d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368    c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99    c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166    c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z"
                        />
                      </g>
                    </g>
                    <g>
                      <g>
                        <circle cx="168" cy="180.12" r="32" />
                      </g>
                    </g>
                    <g>
                      <g>
                        <circle cx="344" cy="180.12" r="32" />
                      </g>
                    </g>
                  </svg>
                </button>
              </template>
            </twemoji-picker>
          </div>
          <div class="message__send">
            <div class="message__image">
              <label
                id="images"
                class="btn btn-lg mb-0"
                @click="$refs.fileInput.click()"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  version="1.1"
                  id="Capa_1"
                  x="0px"
                  y="0px"
                  width="16px"
                  height="16px"
                  viewBox="0 0 550.801 550.8"
                  style="enable-background: new 0 0 550.801 550.8;"
                  xml:space="preserve"
                >
                  <g>
                    <path
                      d="M515.828,61.201H34.972C15.659,61.201,0,76.859,0,96.172v358.458C0,473.942,15.659,489.6,34.972,489.6h480.856   c19.314,0,34.973-15.658,34.973-34.971V96.172C550.801,76.859,535.143,61.201,515.828,61.201z M515.828,96.172V350.51l-68.92-62.66   c-10.359-9.416-26.289-9.04-36.186,0.866l-69.752,69.741L203.438,194.179c-10.396-12.415-29.438-12.537-39.99-0.271L34.972,343.219   V96.172H515.828z M367.201,187.972c0-26.561,21.523-48.086,48.084-48.086c26.562,0,48.086,21.525,48.086,48.086   c0,26.561-21.523,48.085-48.086,48.085C388.725,236.058,367.201,214.533,367.201,187.972z"
                    />
                  </g>
                </svg>
              </label>
              <input
                style="display: none;"
                ref="fileInput"
                type="file"
                @change="fileSelected"
              />
            </div>
            <div class="message__action">
              <button
                class="btn btn-brand btn-lg message-send gb-btn gb-btn-primary"
                :disabled="!isComplete"
                v-bind:class="[sending == true ? 'gb-is-loading' : '']"
                @click="sendMessage(), (sending = true)"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  enable-background="new 0 0 24 24"
                  height="16"
                  viewBox="0 0 24 24"
                  width="16"
                >
                  <path
                    d="m8.75 17.612v4.638c0 .324.208.611.516.713.077.025.156.037.234.037.234 0 .46-.11.604-.306l2.713-3.692z"
                  />
                  <path
                    d="m23.685.139c-.23-.163-.532-.185-.782-.054l-22.5 11.75c-.266.139-.423.423-.401.722.023.3.222.556.505.653l6.255 2.138 13.321-11.39-10.308 12.419 10.483 3.583c.078.026.16.04.242.04.136 0 .271-.037.39-.109.19-.116.319-.311.352-.53l2.75-18.5c.041-.28-.077-.558-.307-.722z"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { TwemojiPicker } from '@kevinfaguiar/vue-twemoji-picker'
import EmojiAllData from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-all-groups.json'
import EmojiDataAnimalsNature from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-group-animals-nature.json'
import EmojiDataFoodDrink from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-group-food-drink.json'
import EmojiGroups from '@kevinfaguiar/vue-twemoji-picker/emoji-data/emoji-groups.json'
import ThreadMessages from '@/buyerDashboard/Activity/Messages/ThreadMessages'

const tableFields = {
  txtMessage: '',
}
export default {
  components: {
    'twemoji-picker': TwemojiPicker,
    ThreadMessages: ThreadMessages,
  },
  props: ['messagesDetails', 'available'],
  data: function () {
    return {
      sending: false,
      baseUrl: baseUrl,
      userData: tableFields,
      uploadImage: false,
      clicked: 0,
    }
  },
  methods: {
    selectEmoji: function (emoji) {
      this.userData.txtMessage += emoji
    },
    fileSelected: function (evt) {
      evt.preventDefault()
      let fileName = evt.target.files[0].name
      let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i
      if (!allowedExtensions.exec(fileName)) {
        alert(this.$t('NOTI_INVALID_FILE_TYPE'))
        return false
      }
      this.selectedFile = evt.target.files[0]
      this.sendMessage()
    },
    newline() {
      this.value = `${this.value}\n`
    },
    sendMessage: function () {
      if (this.userData.txtMessage == '' && this.selectedFile == '') {
        return false
      }
      let formData = this.$serializeData(this.userData)
      formData.append('thread_id', this.messagesDetails.thread_id)
      if (this.selectedFile) {
        this.uploadImage = true
        formData.append('file', this.selectedFile)
        formData.append('txtMessage', this.selectedFile.name)
        setTimeout(() => {
          $('#YK-message-container').scrollTop(
            $('#YK-message-container')[0].scrollHeight,
          )
        }, 500)
      }
      this.$axios
        .post(baseUrl + '/user/send-message', formData)
        .then((response) => {
          this.sending = false
          if (response.data.status == false) {
            toastr.error(response.data.message, '', toastr.options)
            return
          }
          this.selectedFile = ''
          this.messagesDetails.thread_messages.push(response.data.data)
          setTimeout(() => {
            $('#YK-message-container').scrollTop(
              $('#YK-message-container')[0].scrollHeight,
            )
          }, 500)
          this.userData.txtMessage = ''
          this.uploadImage = false
        })
    },
  },
  computed: {
    isComplete() {
      return this.userData.txtMessage.trim()
    },
    emojiDataAll() {
      return EmojiAllData
    },
    emojiGroups() {
      return EmojiGroups
    },
  },
}
</script>
