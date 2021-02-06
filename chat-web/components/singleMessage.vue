<template>
  <div
    class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item"
    v-if="user.id != message.sender.id"
  >
    <div class="options">
      <a href="#">
        <i class="fas fa-angle-down text-muted px-2"></i>
      </a>
    </div>
    <div class="small font-weight-bold text-primary" v-if="message.sender">{{message.sender.name}}</div>

    <div class="d-flex flex-row">
      <div class="body m-1 mr-2">{{ message.message }}</div>
      <div
        class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
        style="width: 75px"
      >{{ created_at }}</div>
    </div>
  </div>

  <div class="align-self-end self p-1 my-1 mx-3 rounded bg-white shadow-sm message-item" v-else>
    <div class="options">
      <a href="#">
        <i class="fas fa-angle-down text-muted px-2"></i>
      </a>
    </div>
    <div class="d-flex flex-row">
      <div class="body m-1 mr-2">{{ message.message }}</div>
      <div
        class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
        style="width: 75px"
      >{{ created_at }}</div>
    </div>
  </div>
</template>
<script>
const moment = require("moment");
export default {
  props: {
    message: {
      type: Object,
      required: true,
    },
  },
  computed: {
    user() {
      return this.$store.state.login.user;
    },
  },
  data() {
    return {
      created_at: null,
    };
  },
  mounted() {
    this.setSendAt();
    setTimeout(() => {
      var objDiv = document.getElementById("messages");
      objDiv.scrollTop = objDiv.scrollHeight;
    }, 300);
  },
  methods: {
    setSendAt() {
      this.interval = setInterval(() => {
        var now = moment(new Date());
        var end = moment(this.message.created_at);
        var duration = moment.duration(now.diff(end));
        var hours = duration.asHours();
        if (hours <= 1) {
          this.created_at = moment(this.message.created_at).fromNow();
        } else if (hours <= 7 * 24) {
          this.created_at = moment(this.message.created_at).calendar();
        } else {
          this.created_at = moment(this.message.created_at).format(
            "YYYY-MMM-DD hh:mm a"
          );
          clearInterval(this.interval);
        }
      }, 100);
    },
  },
};
</script>
