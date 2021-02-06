<template>
  <div id="body" class="align-items-center">
    <div class="container justify-content-center text-center">
      <h1 v-if="error.statusCode === 404">{{error.message ? error.message :'Page not found'}}</h1>
      <h1 v-else>An error occurred</h1>

      <a href="#" class="m-1" @click.prevent="back">Back</a>
      <nuxt-link to="/" class="m-1">Home page</nuxt-link>
    </div>
  </div>
</template>
<script>
export default {
  props: ["error"],
  layout: "blog", // you can set a custom layout for the error page
  methods: {
    back: function () {
      this.$router.go(-1);
    },
  },
};
</script>
<style  scoped>
#body {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  overflow: auto;
  background: #f0f0f0;
  height: 100%;
  display: flex;
}
h1 {
  color: #585858;
}
a {
  color: #585858;
}
a:hover {
  text-decoration: none;
}
</style>