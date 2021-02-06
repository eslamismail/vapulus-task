<template>
  <div id="body">
    <div class="container mb-5">
      <div class="row justify-content-lg-end justify-content-md-center">
        <form
          class="col-md-8 col-12 col-lg-5 card card-body shadow-lg rounded p-5"
          @submit.prevent="login()"
          autocomplete="off"
        >
          <div class="text-center col-md-12 col-12 col-lg-12">
            <div class="row justify-content-center">
              <div class="col-md-5"></div>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-md-3 col-form-label">Email</label>
            <div class="col-md-9">
              <input type="email" name="email" id="email" class="form-control" />
              <Error v-for="(item, index) in errors.email" :error="item" :key="index" />
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-md-3 col-form-label">Password</label>
            <div class="col-md-9">
              <input type="password" name="password" id="password" class="form-control" />
              <Error v-for="(item, index) in errors.password" :error="item" :key="index" />
            </div>
          </div>
          <div class="form-group row">
            <div class="form-group form-check">
              <div class="col-md-12">
                <input type="checkbox" name="remember_me" class="form-check-input" id="remember_me" />
                <label class="form-check-label" for="remember_me">Remeber me</label>
                <Error v-for="(item, index) in errors.remember_me" :error="item" :key="index" />
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12 justify-content-between">
              <button class="btn btn-success text-brand">login</button>

              <div class="mt-2">
                <small>
                  <nuxt-link class="brand-link" to="/register">Need to create account</nuxt-link>
                </small>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import Error from "../components/public/error";

export default {
  components: {
    Error,
  },
  head() {
    return {
      title: "Teleshop login",
    };
  },
  mounted() {},
  created() {},
  data() {
    return {
      errors: {
        email: [],
        password: [],
      },
    };
  },
  computed: {
    user() {
      return this.$store.state.login.user;
    },
    app_logo() {
      return this.$store.state.public.app_logo;
    },
  },
  methods: {
    login: async function () {
      let form = new FormData(event.target);
      try {
        let response = await axios.post("/auth/login", form);
        this.errors = {};
        this.$store.commit("login/login", response.data);
        this.$notify({
          group: "foo",
          text: `welcome back ${this.user.name}`,
          type: "success",
        });
      } catch (error) {
        if (!error.response) {
          this.$notify({
            group: "foo",
            text: `No internet access`,
            type: "error",
          });
        } else if (error.response.status == 422) {
          this.errors = error.response.data.errors;
          console.log(this.errors);
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
  },
};
</script>

<style  scoped>
html,
body {
  margin: 0;
  padding: 0;
}
#body {
  width: 100%;
  background: #67be4a;
  display: flex;
  min-height: 100vh;
  margin: 0;
  padding: 0;
  align-items: center;
}
.brand-link {
  color: #838071;
}
label {
  color: #838071;
}
.form-control {
  color: #838071;
}
.card {
  border-radius: 20px !important;
}
.text-brand {
  font-weight: bold;
  color: #fefefe;
  text-transform: uppercase;
}
</style>
