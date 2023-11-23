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
      newCadaverForm: $(".new-cadaver__form"),
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

      App.DOM.switchDys?.parentElement.addEventListener("keypress", (event) => {
        App.DOM.switchDys.checked = !App.DOM.switchDys.checked;
        if (event.key === "Enter") {
          event.preventDefault();
          const currentAriaChecked = event.target.getAttribute("aria-checked");
          const newAriaChecked =
            currentAriaChecked === "true" ? "false" : "true";
          event.target.setAttribute("aria-checked", newAriaChecked);

          App.toggleDyslexiaMode();
        }
      });

      App.DOM.switchDys?.parentElement.addEventListener("click", (event) => {
        const currentAriaChecked = event.target.getAttribute("aria-checked");
        const newAriaChecked = currentAriaChecked === "true" ? "false" : "true";
        event.target.setAttribute("aria-checked", newAriaChecked);
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

      // App.DOM.newCadaverForm?.addEventListener(
      //   "submit",
      //   App.checkFormNewCadaver
      // );
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
        if (!App.DOM.switchDys) {
          return null;
        } else {
          App.DOM.switchDys.checked = savedDyslexiaMode === "true";
          App.toggleDyslexiaMode();
        }
      }

      const savedDaltonismMode = localStorage.getItem("daltonism");
      if (savedDaltonismMode) {
        App.DOM.htmlElement.classList.add(savedDaltonismMode);
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

    checkFormNewCadaver: (event) => {
      event.preventDefault();
      console.log("Submit");
    },
  };

  window.addEventListener("DOMContentLoaded", App.init);
})();
