require("dotenv").config();

export default {
  ssr: false,
  /*
   ** Headers of the page
   */

  srcDir: __dirname,

  env: {
    apiUrl: process.env.API_URL || process.env.APP_URL + "/api",
    baseURL: process.env.API_URL || process.env.APP_URL,
    appName: process.env.APP_NAME || "Laravel Nuxt",
    appLocale: process.env.APP_LOCALE || "en",
    githubAuth: !!process.env.GITHUB_CLIENT_ID,
    MIX_PUSHER_APP_KEY: process.env.PUSHER_APP_KEY,
  },

  head: {
    title: process.env.npm_package_name || "",
    meta: [
      { charset: "utf-8" },
      { name: "viewport", content: "width=device-width, initial-scale=1" },
      {
        hid: "description",
        name: "description",
        content: process.env.npm_package_description || "",
      },
    ],
    link: [
      {
        href:
          "https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css",
        rel: "StyleSheet",
      },
      {
        href: "https://use.fontawesome.com/releases/v5.0.10/css/all.css",
        rel: "StyleSheet",
      },
      {
        href: "/whats/style.css",
        rel: "StyleSheet",
      },
    ],
    script: [
      { src: "https://code.jquery.com/jquery-3.3.1.slim.min.js", body: true },
      {
        src:
          "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js",
        body: true,
      },
      {
        src:
          "https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js",
        body: true,
      },
    ],
  },
  /*
   ** Customize the progress-bar color
   */
  /*
   ** Global CSS
   */
  css: [],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    { src: "~plugins/axios" },
    { src: "~/plugins/echo.js", ssr: false },
    { src: "~plugins/packages" },
  ],
  components: false,

  buildModules: [],
  /*
   ** Nuxt.js dev-modules
   */

  /*
   ** Nuxt.js modules
   */
  modules: [],

  /*
   ** Build configuration
   */
  build: {
    extend(config, { isDev, isClient }) {
      config.node = {
        fs: "empty",
      };
    },
  },
};
