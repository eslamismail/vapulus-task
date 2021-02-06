import Vue from "vue";
import Notifications from "vue-notification";
Vue.use(Notifications);

const moment = require("moment");
moment.updateLocale("en", {
  relativeTime: {
    future: "in %s",
    past: "%s ago",
    s: number => number + " seconds ",
    ss: "%ds ",
    m: "1 minute ",
    mm: "%d minutes ",
    h: "1 hour ",
    hh: "%d hours ",
    d: "1 day ",
    dd: "%d days ",
    M: "a month ",
    MM: "%d months ",
    y: "a year ",
    yy: "%d years "
  }
});
