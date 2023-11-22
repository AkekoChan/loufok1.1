"use strict";
(function () {
  const App = {
    DOM: {
      bellBtn: $(".header__bell"),
      popUpNotif: $(".header__popup"),
      accessibilityBtn: $(".header__accessibility-btn"),
      popUpAccessibility: $(".accessibility-popup"),
      switchDys: $(".switch-dyslexic input"),
      increaseBtn: $(".increaseFontButton"),
      decreaseBtn: $(".decreaseFontButton"),
      resetBtn: $(".resetFontButton"),
      htmlElement: $("html"),
      themeButtons: $$(".theme-button"),
      btns: $$(".btn"),
    },
    init: () => {
      App.event();
      App.restoreState();
      App.runGSAPAnimation();
    },
    event: () => {
      App.DOM.bellBtn?.addEventListener("click", () => {
        App.togglePopUp(App.DOM.popUpNotif);
        App.closeOtherPopups(App.DOM.popUpNotif);
      });

      App.DOM.accessibilityBtn?.addEventListener("click", () => {
        App.togglePopUp(App.DOM.popUpAccessibility);
        App.closeOtherPopups(App.DOM.popUpAccessibility);
      });

      App.DOM.switchDys?.parentElement.addEventListener("keypress", (event) => {
        App.DOM.switchDys.checked = !App.DOM.switchDys.checked;
        if (event.key === "Enter") {
          App.toggleDyslexiaMode();
        }
      });

      App.DOM.switchDys?.parentElement.addEventListener("click", () => {
        App.toggleDyslexiaMode();
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

      App.DOM.themeButtons?.forEach((button) => {
        button.addEventListener("click", () => {
          const value = button.getAttribute("data-value");
          App.changeDaltonism(value);
        });
      });
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
      App.DOM.htmlElement.classList.toggle(
        "dyslexia-mode",
        App.DOM.switchDys.checked
      );
      localStorage.setItem("dyslexiaMode", App.DOM.switchDys.checked);
    },

    /**
     * Augmente la taille de la police du navigateur
     */
    increaseFontSize: () => {
      const currentSize = parseFloat(
        window.getComputedStyle(App.DOM.htmlElement).fontSize
      );
      const newSize = Math.min(currentSize * 1.1, 24); // Limite à 24px
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

      const savedDyslexiaMode = localStorage.getItem("dyslexiaMode");
      if (savedDyslexiaMode !== null) {
        App.DOM.switchDys.checked = savedDyslexiaMode === "true";
        App.toggleDyslexiaMode();
      }
      const savedDaltonismMode = localStorage.getItem("daltonism");
      if (savedDaltonismMode) {
        App.DOM.htmlElement.classList.add(savedDaltonismMode);
      }
    },

    runGSAPAnimation: () => {
      gsap.set(".right-fade", { opacity: 0, x: 70 });

      gsap.to(".right-fade", {
        opacity: 1,
        x: 0,
        duration: 0.4,
        ease: "power1.out",
        stagger: 0.5,
      });

      gsap.set(".bottom-fade", { opacity: 0, y: 50 });

      gsap.to(".bottom-fade", {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: "power2.out",
        stagger: 0.1,
      });

      gsap.from(".pop-in", { scale: 0, ease: "power1-out", duration: 0.4 });

      App.DOM.btns?.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          gsap.fromTo(
            ".btn",
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
    },
  };

  window.addEventListener("DOMContentLoaded", App.init);
})();
