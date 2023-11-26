'use strict';
(function(){
    const Control = {
        $: {
            form: $('.new-cadaver__form'),
            title: $('#cadaver-title'),
            maxcount: $('#contributions-count'),
            calendar: $('.js-calendar'),
            periode: {
                start: $('#dateStart'),
                end: $('#dateEnd')
            }
        },
        init: async () => {
            Control.$.form.addEventListener('submit', Control.submit);
            Control.data = await $get("/loufok/internal/controls/json");
            Calendar.importPeriodes(Control.data.periodes);
            console.log("Controls loaded");
        },
        submit: (evt) => {
            evt.preventDefault();
            try {
                if(Control.data?.titles.includes(Control.$.title.value)) {
                    throw({
                        title: "Un cadavre avec ce titre existe déjà. Veuillez en choisir un autre.",
                        input: Control.$.title
                    });
                }
                if(isNaN(Control.$.maxcount.value) || Control.$.maxcount.value < 1) {
                    throw({
                        title: "Le nombre de contributions doit être supérieur ou égal à 1.",
                        input: Control.$.maxcount
                    });
                }
                if(Control.$.periode.end.value < Control.$.periode.start.value) {
                    throw({
                        title: "La date de fin ne peut pas être inférieur à la date de début.",
                        input: Control.$.calendar
                    });
                }
                if(Control.$.periode.start.value < new Date().toISOString().split("T")[0]) {
                    throw({
                        title: "La date de début ne peut pas être inférieur à la date d'aujourd'hui.",
                        input: Control.$.calendar
                    });
                }

                let start = Control.$.periode.start.value;
                let end = Control.$.periode.end.value;
                let controlOverlap = Control.data.periodes.filter((item) => {
                    return start <= item.end && end >= item.start;
                });

                if(controlOverlap.length > 0) {
                    throw({
                        title: "La période chevauche une autre période.",
                        input: Control.$.calendar
                    });
                }
                
                evt.target.submit();
            } catch (error) {
                error.input.setCustomValidity(error.title);
                error.input.reportValidity();
                error.input.setCustomValidity("");
            }
        }
    }

    document.addEventListener('DOMContentLoaded', Control.init);
})();