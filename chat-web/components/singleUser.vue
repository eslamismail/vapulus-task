<template>
  <div
    :class="`chat-list-item d-flex flex-row w-100 p-2 border-bottom ${
      activeRoom && activeRoom.id == room.id ? 'active' : ''
    }`"
    @click.prevent="setActive()"
  >
    <img
      :src="room.room_image"
      :alt="room.room_name"
      class="img-fluid rounded-circle mr-2"
      style="height: 50px"
    />
    <div class="w-50">
      <div class="name">{{ room.room_name }}</div>
      <div class="small last-message">
        {{ typing && activeRoom.id == room.id ? typing : room.last_message }}
      </div>
    </div>
    <div class="flex-grow-1 text-right">
      <div class="small time">{{ created_at }}</div>
    </div>
  </div>
</template>
<script>
const moment = require("moment");
export default {
  props: {
    room: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      created_at: null,
    };
  },
  computed: {
    activeRoom() {
      return this.$store.state.chat.activeRoom;
    },
    typing() {
      return this.$store.state.chat.typing;
    },
  },
  mounted() {
    this.solveDate();
  },
  methods: {
    solveDate() {
      this.interval = setInterval(() => {
        var now = moment(new Date());
        var end = moment(this.room.message_send_at);
        var duration = moment.duration(now.diff(end));
        var hours = duration.asHours();
        if (hours <= 1) {
          this.created_at = moment(this.room.message_send_at).fromNow();
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
    setActive() {
      return this.$emit("setActive", this.room);
    },
  },
};
</script>
