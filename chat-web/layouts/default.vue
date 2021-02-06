<template>
  <div>
    <notifications group="foo" class="m-1 p-3" />
    <nuxt />
  </div>
</template>
<script>
export default {
  head() {
    return {
      title: "Teleshop",
      link: [{ rel: "icon", href: this.app_logo }],
    };
  },
  computed: {
    app_logo() {
      return this.$store.state.public.app_logo;
    },
  },
};
</script>
<style>
.vue-notification {
  font-size: 14px;
  cursor: pointer;
}
</style>
