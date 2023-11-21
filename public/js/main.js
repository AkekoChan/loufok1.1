"use strict";
(function () {
  const App = {
    DOM: {
      bell: $(".header__bell"),
      popUp: $(".header__popup"),
    },
    init: () => {
      App.event();
    },
    event: () => {
      App.DOM.bell?.addEventListener("click", App.toggleNotifs);
    },
    toggleNotifs: () => {
      App.DOM.popUpbell?.classList.toggle("toggle");
    },
  };

  window.addEventListener("DOMContentLoaded", App.event());
})();
