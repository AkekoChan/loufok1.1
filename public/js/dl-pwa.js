"use strict";
(function () {
  const App = {
    DOM: {
      pwaInstallButton: $(".btn-third"),
    },

    CONST: {
      installPrompt: null,
    },

    init: () => {
      App.event();
    },

    event: () => {
      window.addEventListener("beforeinstallprompt", (event) => {
        event.preventDefault();
        App.CONST.installPrompt = event;

        App.DOM.pwaInstallButton.addEventListener("click", App.installPwa);
      });
    },

    installPwa: async () => {
      if (!App.CONST.installPrompt) return;

      try {
        await App.CONST.installPrompt.prompt();
        const result = await App.CONST.installPrompt.userChoice;

        console.log("Install prompt result", result);

        // Notification.requestPermission().then((result) => {
        //   if (result === "granted") {
        //     console.log("Notifications granted");
        //   } else {
        //     console.log("Notifications refusées");
        //   }
        // });
      } catch (error) {
        console.error("Erreur lors de l'installation de PWA", error);
      } finally {
        App.CONST.installPrompt = null;
      }
    },
  };

  window.addEventListener("DOMContentLoaded", App.init);
})();
