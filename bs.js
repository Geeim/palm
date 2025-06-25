const browserSync = require("browser-sync").create();

browserSync.init({
  proxy: "localhost:8000",                    // iyong PHP server
  files: ["**/*.php", "**/*.css", "**/*.js"], // mga file na babantayan
  open: true,                                 // auto-open browser
  notify: false,                              // walang popup sa gilid
  port: 3000                                  // saan tatakbo si BrowserSync
});
