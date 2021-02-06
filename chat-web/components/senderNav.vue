<template>
  <div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">
    <div class="d-block d-sm-none">
      <i
        class="fas fa-arrow-left p-2 mr-2 text-white"
        style="font-size: 1.5rem; cursor: pointer"
        onclick="showChatList()"
      ></i>
    </div>
    <a href="#"
      ><img
        :src="room.room_image"
        alt="Profile Photo"
        class="img-fluid rounded-circle mr-2"
        style="height: 50px"
        id="pic"
    /></a>
    <div class="d-flex flex-column">
      <div class="text-white font-weight-bold" id="name">
        {{ room.room_name }}
      </div>
      <div class="text-white small" id="details">
        {{ typing ? typing : created_at }}
      </div>
    </div>
    <div class="d-flex flex-row align-items-center ml-auto">
      <a href="#">
        <i class="fas fa-search mx-3 text-white d-none d-md-block"></i>
      </a>
      <a href="#">
        <i class="fas fa-paperclip mx-3 text-white d-none d-md-block"></i>
      </a>
      <a href="#">
        <i class="fas fa-ellipsis-v mr-2 mx-sm-3 text-white"></i>
      </a>
      <a href="#" @click.prevent="exitRoom()">
        <i class="fas fa-times mr-2 mx-sm-3 text-white"></i>
      </a>
    </div>
  </div>
</template>
<script>
const moment = require("moment");
export default {
  computed: {
    room() {
      return this.$store.state.chat.activeRoom;
    },
    typing() {
      return this.$store.state.chat.typing;
    },
  },
  data() {
    return {
      created_at: null,
    };
  },
  mounted() {
    this.setCreatedAt();
  },
  methods: {
    setCreatedAt() {
      this.interval = setInterval(() => {
        var now = moment(new Date());
        var end = moment(this.room.message_send_at);
        var duration = moment.duration(now.diff(end));
        var hours = duration.asHours();
        if (hours <= 1) {
          this.created_at =
            "About " + moment(this.room.message_send_at).fromNow();
        } else if (hours <= 7 * 24) {
          this.created_at = moment(this.room.message_send_at).calendar();
        } else {
          this.created_at = moment(this.room.message_send_at).format(
            "YYYY-MMM-DD hh:mm a"
          );
          clearInterval(this.interval);
        }
      }, 100);
    },
    exitRoom() {
      Echo.leave(`chat-${this.room.id}`);
      this.$store.commit("chat/setActive", {});
    },
  },
};
</script>
