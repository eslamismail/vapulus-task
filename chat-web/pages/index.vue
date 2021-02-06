<template>
  <div class="container-fluid" id="main-container">
    <div class="row h-100">
      <div
        class="col-12 col-sm-5 col-md-4 d-flex flex-column"
        id="chat-list-area"
        style="position: relative"
      >
        <!-- Navbar -->
        <nav-area />
        <!-- Chat List -->
        <chat-area />
        <!-- Profile Settings -->
        <profile-area />
      </div>

      <!-- Message Area -->
      <message-area />
    </div>
  </div>
</template>
<script>
import messageArea from "../components/messageArea";
import profileArea from "../components/profileArea";
import chatArea from "../components/chatArea";
import navArea from "../components/navArea";
const underscore = require("underscore");
const moment = require("moment");
export default {
  middleware: "auth",
  components: { messageArea, profileArea, chatArea, navArea },
  head() {
    return {};
  },
  mounted() {
    this.getRooms();
    Echo.private(`room-${this.user.id}`)
      .listen("NewRoom", (e) => {
        this.$store.commit("chat/addRoom", e.room);
      })
      .listen("RoomUpdated", (e) => {
        this.$store.commit("chat/updateLastMessage", e.room);
      });
  },
  // beforeRouteLeave(to, from, next) {
  //   // Echo.leave(`room-${this.user.id}`);
  // },
  created() {
    console.log("object", "CREATED");
  },
  computed: {
    user() {
      return this.$store.state.login.user;
    },
  },
  methods: {
    async getRooms() {
      try {
        let response = await axios.get("/rooms");
        const { rooms } = response.data;
        this.$store.commit("chat/createRooms", rooms);
      } catch (error) {
        if (!error.response) {
          this.$notify({
            group: "foo",
            text: `No internet access`,
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
        } else {
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
        }
      }
    },
  },
};
</script>
