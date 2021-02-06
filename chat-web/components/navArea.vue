<template>
  <div class="row d-flex flex-row align-items-center p-2" id="navbar">
    <img
      alt="Profile Photo"
      class="img-fluid rounded-circle mr-2"
      style="height: 50px; cursor: pointer"
      id="display-pic"
      v-if="user"
      :src="user.profile_picture_url"
    />
    <div v-if="user" class="text-white font-weight-bold" id="username">{{ user.name }}</div>
    <div class="nav-item dropdown ml-auto">
      <users-modal :users="users" />

      <group-modal :users="users" />
      <a
        class="nav-link dropdown-toggle"
        data-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <i class="fas fa-ellipsis-v text-white"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" @click.prevent="getUsers()" href="#">New Chat</a>
        <a class="dropdown-item" @click.prevent="getGroup()" href="#">New Group</a>
        <a class="dropdown-item" href="#">Starred</a>
        <a class="dropdown-item" href="#">Settings</a>
        <a class="dropdown-item" @click.prevent="logout" href="#">Log Out</a>
      </div>
    </div>
  </div>
</template>
<script>
import usersModal from "./usersModal";
import groupModal from "./groupModal";

export default {
  components: {
    usersModal,
    groupModal,
  },
  data() {
    return {
      users: [],
    };
  },
  computed: {
    user() {
      return this.$store.state.login.user;
    },
  },
  methods: {
    async logout() {
      try {
        await axios.post("/logout");
        this.$store.commit("login/logout", {});
      } catch (error) {}
      this.$store.commit("login/logout", {});
    },
    async getUsers() {
      try {
        let response = await axios.get("/users");
        this.users = response.data.users;
        if (this.users.length == 0) {
          this.$notify({
            group: "foo",
            text: `No users found`,
            type: "error",
          });
          return false;
        }
        $("#users-modal").modal("show");
      } catch (error) {
        console.log(error);
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
    async getGroup() {
      try {
        let response = await axios.get("/users/all");
        this.users = response.data.users;
        $("#group-modal").modal("show");
        if (this.users.length == 0) {
          this.$notify({
            group: "foo",
            text: `No users found`,
            type: "error",
          });
          return false;
        }
        // $("#users-modal").modal("show");
      } catch (error) {
        console.log(error);
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
