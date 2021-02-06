<template>
  <div id="body">
    <div class="container">
      <div class="row justify-content-lg-end justify-content-md-center">
        <form
          class="col-md-8 col-12 col-lg-5 card card-body shadow-lg rounded"
          autocomplete="off"
          @submit.prevent="signup"
        >
          <div class="text-center col-md-12 col-12 col-lg-12">
            <div class="row justify-content-center">
              <div class="col-md-5 col-lg-5 col-5"></div>
            </div>
          </div>

          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-capitalize">name</label>
            <div class="col-md-8">
              <input type="text" name="name" id="name" class="form-control" />
              <Error v-for="(item, index) in errors.name" :error="item" :key="index" />
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label">Email</label>
            <div class="col-md-8">
              <input type="email" name="email" id="email" class="form-control" />
              <Error v-for="(item, index) in errors.email" :error="item" :key="index" />
            </div>
          </div>

          <div class="form-group row">
            <label for="image" class="col-md-4 col-form-label text-capitalize">image</label>
            <div class="col-md-8">
              <input accept="image/*" type="file" name="image" id="image" class="form-control" />
              <Error v-for="(item, index) in errors.image" :error="item" :key="index" />
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-capitalize">Password</label>
            <div class="col-md-8">
              <input type="password" name="password" id="password" class="form-control" />
              <Error v-for="(item, index) in errors.password" :error="item" :key="index" />
            </div>
          </div>

          <div class="form-group row">
            <label
              for="password_confirmation"
              class="col-md-4 col-form-label text-capitalize"
            >Password confirmation</label>
            <div class="col-md-8">
              <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="form-control"
              />
              <Error
                v-for="(item, index) in errors.password_confirmation"
                :error="item"
                :key="index"
              />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12 justify-content-between">
              <div class="mt-2">
                <button class="btn btn-success text-brand">register</button>
              </div>
              <div class="mt-2">
                <small>
                  <nuxt-link class="brand-link" to="/login">Already has account</nuxt-link>
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
      title: "Teleshop register",
    };
  },
  data() {
    return {
      errors: {},
    };
  },
  computed: {
    app_logo() {
      return this.$store.state.public.app_logo;
    },
  },
  methods: {
    signup: async function () {
      let form = new FormData(event.target);
      try {
        let response = await axios.post("/auth/register", form);
        this.errors = {};
        this.$store.commit("login/login", response.data);
        const { message } = response.data;
        this.$notify({
          group: "foo",
          text: message,
          type: "success",
        });
        console.log(response.data);
      } catch (error) {
        console.log(error.response);
        if (!error.response) {
          this.errors = {};
          this.$notify({
            group: "foo",
            text: `No internet access`,
            type: "error",
          });
        } else if (error.response.status == 422) {
          this.errors = error.response.data.errors;
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
        } else if (error.response.status == 401) {
          this.errors = {};
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
          // $nuxt.$store.commit("login/logout", {});
        } else if (error.response.status == 400) {
          this.errors = {};
          let { message } = error.response.data;
          this.$notify({
            group: "foo",
            text: message,
            type: "error",
          });
        } else {
          let { statusText } = error.response.data;
          this.errors = {};
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
  align-items: center;
  margin: 0;
  padding: 0;
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
