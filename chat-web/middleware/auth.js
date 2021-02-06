export default async function({ store, redirect }) {
  try {
    let response = await axios.post("auth/me");
    let { user } = response.data;
    $nuxt.$store.commit("login/refresh", user);
  } catch (error) {
    if (!error.response) {
      $nuxt.user = null;
      $nuxt.$store.commit("login/logout", {});
    } else if (error.response.status == 401) {
      $nuxt.user = null;
      $nuxt.$store.commit("login/logout", {});
    }
  }
}
