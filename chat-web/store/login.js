export const state = () => ({
  token: window.localStorage.getItem("token") || null,
  user: null,
});

export const mutations = {
  login: (state, data) => {
    window.localStorage.setItem("token", data.access_token);
    state.user = data.user;
    axios.defaults.headers.authorization = `Bearer ${data.access_token}`;
    Echo.connector.pusher.config.auth.headers[
      "Authorization"
    ] = `Bearer ${data.access_token}`;

    $nuxt.$router.push("/");
  },
  refresh(state, data) {
    state.user = data;
  },
  logout(state, data = {}) {
    $nuxt.$router.push("/login");
    state.token = null;
    state.user = null;
    Echo.connector.pusher.config.auth.headers[
      "Authorization"
    ] = `Bearer ${data.access_token}`;
    axios.defaults.headers.authorization = `Bearer ${data.access_token}`;
    window.localStorage.removeItem("token");
    $nuxt.$store.commit("chat/resetData", {});
  },
};
