<template>
  <form @submit.prevent="sendMessage()" autocomplete="off" id="send-message">
    <picker v-if="emoji" id="emojione" class="w-100" set="emojione" @select="select" />
    <div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
      <a href="#" @click.prevent="setEmoji()">
        <i class="far fa-smile text-muted px-3" style="font-size: 1.5rem"></i>
      </a>
      <input
        type="text"
        name="message"
        id="input"
        placeholder="Type a message"
        class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm"
        @change="typing()"
      />
      <i
        class="fas fa-paper-plane text-muted px-3"
        @click.prevent="sendMessage"
        style="cursor: pointer"
      ></i>
    </div>
  </form>
</template>
<script>
import { Picker } from "emoji-mart-vue";
export default {
  components: { Picker },
  computed: {
    room() {
      return this.$store.state.chat.activeRoom;
    },
    user() {
      return this.$store.state.login.user;
    },
  },
  data() {
    return {
      emoji: false,
    };
  },

  methods: {
    select(item) {
      // console.log(item.native);
      let value = document.getElementById("input").value;
      document.getElementById("input").value = value + item.native;
    },
    setEmoji() {
      if (this.emoji) {
        this.emoji = false;
      } else {
        this.emoji = true;
      }
    },

    async sendMessage() {
      try {
        let element = document.getElementById("send-message");
        let form = new FormData(element);
        await axios.post(`rooms/${this.room.id}/message`, form);
        element.reset();
      } catch (error) {
        if (!error.response) {
          this.$notify({
            group: "foo",
            text: `No internet access`,
            type: "error",
          });
        } else if (error.response.status == 422) {
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
        } else if (error.response.status == 401) {
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
          $nuxt.$store.commit("login/logout", {});
        } else if (error.response.status == 400) {
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
        } else {
          let { statusText } = error.response.data;
          this.$notify({
            group: "foo",
            text: statusText,
            type: "error",
          });
        }
      }
    },
    typing() {
      this.emoji = false;
      Echo.private(`chat-${this.room.id}`).whisper("typing", {
        user: this.user,
      });
    },
  },
};
</script>
