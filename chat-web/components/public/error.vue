<template>
  <div class="m-1">
    <span class="text-danger">{{error}}</span>
  </div>
</template>
<script>
export default {
  props: {
    error: {
      type: String,
      required: true,
    },
  },
};
</script>
