<template>
  <div class="modal fade" role="dialog" id="users-modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">New chat</h5>
        </div>
        <div class="modal-body">
          <div
            v-for="(user, index) in users"
            :key="index"
            class="m-2 shadow row card"
            @click="selectUser(user)"
          >
            <div class="justify-content-between col-12">
              <img
                v-if="user"
                :src="user.profile_picture_url"
                width="20%"
                class="img-thumbnail"
                alt
              />
              <div v-if="user" class="d-inline">{{ user.name }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    users: {
      type: Array,
      required: true,
    },
  },
  methods: {
    async selectUser(user) {
      try {
        await axios.post("/rooms/create", { user_id: user.id });
        $("#users-modal").modal("hide");
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
