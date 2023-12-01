"use strict";
(function () {
  const App = {
    DOM: {
      bellBtn: $(".header__bell"),
      popUpNotif: $(".header__popup"),
      accessibilityBtn: $(".header__accessibility-btn"),
      popUpAccessibility: $(".accessibility-popup"),
      switchDys: $(".switch-dyslexic"),
      switchAnimation: $(".switch-animation"),
      increaseBtn: $(".increaseFontButton"),
      decreaseBtn: $(".decreaseFontButton"),
      resetBtn: $(".resetFontButton"),
      htmlElement: $("html"),
      daltonismSelect: $("#daltonism-select"),
      btns: $$(".btn"),
      newCadaverForm: $(".new-cadaver__form"),
    },

    init: async () => {
      App.event();
      App.restoreState();

      let subscription = await navigator.serviceWorker.getRegistration();

      if(Notification.permission === "granted" && !subscription) {
        await App.registerServiceWorker();
      }

      if(Notification.permission !== "granted") {
        document.addEventListener('click', App.requestNotifications);
      }
    },

    requestNotifications: async () => {
      Notification.requestPermission().then(async (result) => {
        if(result === "granted") {
          console.log("Notifications Accepted - Registrating ServiceWorker");
          document.removeEventListener('click', App.requestNotifications);
          await App.registerServiceWorker();
        } else {
          alert("Vous n'avez pas authorisé les notifications");
        }
      });
    },

    registerServiceWorker: async () => {
      if ("serviceWorker" in navigator) {
        const registration = await navigator.serviceWorker.register("/loufok/js/sw.js");
        console.log(registration);

        let subscription = await registration.pushManager.getSubscription();
        if (subscription) return;

        subscription = await registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: 'BHpS58XK5JwhUXEXEnQxknmS8SMDnuI9oz5Ow0WrBsXTjFIMA3SwVlI3OEz953K6c36VCAeF7zjWZ-sayn2Whmg'
        }); await $post("/loufok/subscribe", subscription);
      }
    },

    event: () => {
      App.DOM.bellBtn?.addEventListener("click", () => {
        App.togglePopUp(App.DOM.popUpNotif);
        App.closeOtherPopups(App.DOM.popUpNotif);
      });

      App.DOM.bellBtn?.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
          App.togglePopUp(App.DOM.popUpNotif);
          App.closeOtherPopups(App.DOM.popUpNotif);
        }
      });

      App.DOM.accessibilityBtn?.addEventListener("click", () => {
        App.togglePopUp(App.DOM.popUpAccessibility);
        App.closeOtherPopups(App.DOM.popUpAccessibility);
      });

      App.DOM.accessibilityBtn?.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
          App.togglePopUp(App.DOM.popUpAccessibility);
          App.closeOtherPopups(App.DOM.popUpAccessibility);
        }
      });

      App.DOM.increaseBtn?.addEventListener("click", () => {
        App.increaseFontSize();
      });

      App.DOM.decreaseBtn?.addEventListener("click", () => {
        App.decreaseFontSize();
      });

      App.DOM.resetBtn?.addEventListener("click", () => {
        App.resetFontSize();
      });

      App.DOM.daltonismSelect?.addEventListener("change", () => {
        const value = App.DOM.daltonismSelect.value;
        App.changeDaltonism(value);
      });

      App.DOM.switchDys?.addEventListener("keydown", (event) => {
        if (event.key === " " || event.key === "Enter") {
          event.preventDefault();

          App.handleSwitch(App.DOM.switchDys, "dyslexiaMode");
          App.toggleDyslexiaMode();
        }
      });

      App.DOM.switchDys?.addEventListener("click", () => {
        App.handleSwitch(App.DOM.switchDys, "dyslexiaMode");
        App.toggleDyslexiaMode();
      });

      App.DOM.switchAnimation?.addEventListener("keydown", (event) => {
        if (event.key === " " || event.key === "Enter") {
          event.preventDefault();

          App.handleSwitch(App.DOM.switchAnimation, "disableAnimations");
        }
      });

      App.DOM.switchAnimation?.addEventListener("click", () => {
        App.handleSwitch(App.DOM.switchAnimation, "disableAnimations");
      });

      window.addEventListener("click", function (e) {
        if (!$(".header__accessibility")?.contains(e.target)) {
          App.DOM.popUpAccessibility?.classList.remove("toggle");
        }
      });
    },

    handleSwitch: (toggler, localStorageName) => {
      toggler.classList.toggle("active");
      // Modifies status contents

      let switchIsActive = toggler.getAttribute("aria-checked");
      console.log(typeof switchIsActive);

      if (switchIsActive === "true") {
        switchIsActive = "false";
      } else {
        switchIsActive = "true";
      }

      // Toggle aria-checked
      toggler.setAttribute("aria-checked", switchIsActive);
      localStorage.setItem(`${localStorageName}`, switchIsActive);
    },
    /**
     * Ouvre ou ferme un popup en fonction de son état actuel
     * @param {*} target - L'élément du popup ciblé
     */
    togglePopUp: (target) => {
      target?.classList.toggle("toggle");
    },

    /**
     * Ferme tous les popups sauf celui qui est actuellement cliqué
     * @param {*} currentPopup - Le popup actuellement cliqué
     */
    closeOtherPopups: (currentPopup) => {
      const popupsToClose = $$(".header__popup, .accessibility-popup");
      popupsToClose.forEach((popup) => {
        if (popup !== currentPopup) {
          popup.classList.remove("toggle");
        }
      });
    },

    /**
     * Active ou désactive le mode dyslexique
     */
    toggleDyslexiaMode: () => {
      App.DOM.htmlElement.classList.toggle("dyslexia-mode");
    },

    /**
     * Augmente la taille de la police du navigateur
     */
    increaseFontSize: () => {
      const currentSize = parseFloat(
        window.getComputedStyle(App.DOM.htmlElement).fontSize
      );
      const newSize = Math.min(currentSize * 1.1, 20); // Limite à 20px
      App.DOM.htmlElement.style.fontSize = `${newSize}px`;
      localStorage.setItem("fontSize", newSize);
    },

    /**
     * Diminue la taille de la police du navigateur
     */
    decreaseFontSize: () => {
      const currentSize = parseFloat(
        window.getComputedStyle(App.DOM.htmlElement).fontSize
      );
      const newSize = Math.max(currentSize * 0.9, 10); // Limite à 10px
      App.DOM.htmlElement.style.fontSize = `${newSize}px`;
      localStorage.setItem("fontSize", newSize);
    },

    /**
     * Réinitialise la taille de la police du navigateur
     */
    resetFontSize: () => {
      const newSize = 16; // Réinitialise la police à 16px
      App.DOM.htmlElement.style.fontSize = `${newSize}px`;
      localStorage.setItem("fontSize", newSize);
    },

    /**
     * Active le mode daltonisme avec le type spécifié
     * @param {*} value - Le type de daltonisme à activer
     */
    changeDaltonism: (value) => {
      App.DOM.htmlElement.classList.remove(
        "normal",
        "achromatopsia",
        "protanopia",
        "tritanopia",
        "deuteranopia"
      );
      App.DOM.htmlElement.classList.add(value);
      localStorage.setItem("daltonism", value);
    },

    /**
     * Charge les états sauvegardés des différents modes
     */
    restoreState: () => {
      const savedFontSize = localStorage.getItem("fontSize");
      if (savedFontSize !== null) {
        App.DOM.htmlElement.style.fontSize = `${savedFontSize}px`;
      }

      const savedDaltonismMode = localStorage.getItem("daltonism");
      if (savedDaltonismMode) {
        App.DOM.htmlElement.classList.add(savedDaltonismMode);
      }

      const savedDyslexiaMode = localStorage.getItem("dyslexiaMode");
      if (savedDyslexiaMode === "true") {
        App.DOM.switchDys.classList.toggle("active");
        App.DOM.switchDys.setAttribute("aria-checked", "true");
        App.toggleDyslexiaMode();
      }

      const savedDisabledAnimation = localStorage.getItem("disableAnimations");
      if (
        savedDisabledAnimation === "false" ||
        savedDisabledAnimation === null
      ) {
        console.log("load animation");
        App.runGSAPAnimation();
      }

      if (savedDisabledAnimation === "true") {
        console.log("disabled anim");
        App.DOM.switchAnimation.classList.toggle("active");
        App.DOM.switchAnimation.setAttribute("aria-checked", "true");
      }
    },

    /**
     *  Ensemble des animations en GSAP
     */
    runGSAPAnimation: () => {
      const rightFadeElements = document.querySelectorAll(".right-fade");
      const bottomFadeElements = document.querySelectorAll(".bottom-fade");
      const popInElements = document.querySelectorAll(".pop-in");

      if (rightFadeElements.length > 0) {
        gsap.set(rightFadeElements, { opacity: 0, x: 70 });

        gsap.to(rightFadeElements, {
          opacity: 1,
          x: 0,
          duration: 0.4,
          ease: "power1.out",
          stagger: 0.5,
        });
      }

      if (bottomFadeElements.length > 0) {
        gsap.set(bottomFadeElements, { opacity: 0, y: 50 });

        gsap.to(bottomFadeElements, {
          opacity: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
          stagger: 0.1,
        });
      }

      if (popInElements.length > 0) {
        gsap.from(popInElements, {
          scale: 0,
          ease: "power1-out",
          duration: 0.4,
        });
      }

      const btns = App.DOM.btns;

      if (btns) {
        btns.forEach((btn) => {
          btn.addEventListener("click", (e) => {
            gsap.fromTo(
              btn,
              { scale: 1 },
              {
                scale: 0.85,
                duration: 0.15,
                yoyo: true,
                repeat: 1,
                overwrite: true,
              }
            );
          });
        });
      }
    },
  };

  window.addEventListener("DOMContentLoaded", App.init);
})();
