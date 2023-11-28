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
            },
            keepBtn: $('#keepData')
        },
        init: async () => {
            Control.$.form.addEventListener('submit', Control.submit);
            Control.data = await $get("/loufok/internal/controls/json");
            Calendar.importPeriodes(Control.data.periodes);
            console.log("Controls loaded");

            Control.$.title.addEventListener('change', (evt) => {
                if(!Control.data.titles.includes(evt.target.value)) return evt.target.setCustomValidity("");
                evt.target.setCustomValidity("Un cadavre avec ce titre existe déjà. Veuillez en choisir un autre.");
                evt.target.reportValidity();
            });

            Control.storeID = document.getElementById('storeID')?.value;

            if(!Control.storeID) return;

            Control.loadStoreData();
            Control.$.keepBtn.addEventListener('click', Control.saveStoreData);
            // window.addEventListener('beforeunload', Control.saveStoreData);
        },
        loadStoreData: () => {
            let data = JSON.parse(localStorage.getItem(Control.storeID));
            // console.log(data);
            if(!data) return;
            Control.$.title.value = data.title;
            Control.$.maxcount.value = data.maxcount;
            Control.$.periode.start.value = data.periode_start;
            Control.$.periode.end.value = data.periode_end;
            Calendar.init();
        },
        saveStoreData: () => {
            let data = {
                title: Control.$.title.value,
                maxcount: Control.$.maxcount.value,
                periode_start: Control.$.periode.start.value,
                periode_end: Control.$.periode.end.value
            }

            localStorage.setItem(Control.storeID, JSON.stringify(data));
        },
        submit: (evt) => {
            evt.preventDefault();
            try {
                if(Control.data?.titles.includes(Control.$.title.value)) {
                    throw({
                        title: "Un cadavre avec ce titre existe déjà. Veuillez en choisir un autre.",
                        input: Control.$.title
                    });
                } else {
                    Control.$.title.setCustomValidity("")
                }

                if(isNaN(Control.$.maxcount.value) || Control.$.maxcount.value < 1) {
                    throw({
                        title: "Le nombre de contributions doit être supérieur ou égal à 1.",
                        input: Control.$.maxcount
                    });
                } else {
                    Control.$.maxcount.setCustomValidity("")
                }

                if(Control.$.periode.end.value < Control.$.periode.start.value) {
                    throw({
                        title: "La date de fin ne peut pas être inférieur à la date de début.",
                        input: Control.$.periode.start
                    });
                } else {
                    Control.$.periode.start.setCustomValidity("")
                }

                if(Control.$.periode.start.value < new Date().toISOString().split("T")[0]) {
                    throw({
                        title: "La date de début ne peut pas être inférieur à la date d'aujourd'hui.",
                        input: Control.$.periode.start
                    });
                } else {
                    Control.$.periode.start.setCustomValidity("")
                }

                let start = Control.$.periode.start.value;
                let end = Control.$.periode.end.value;
                let controlOverlap = Control.data.periodes.filter((item) => {
                    return start <= item.end && end >= item.start;
                });

                if(controlOverlap.length > 0) {
                    throw({
                        title: "La période chevauche une autre période.",
                        input: Control.$.periode.start
                    });
                } else {
                    Control.$.periode.start.setCustomValidity("")
                }
                
                localStorage.removeItem(Control.storeID);
                evt.target.submit();
            } catch (error) {
                console.error(error);
                error.input.setCustomValidity(error.title);
                error.input.reportValidity();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', Control.init);
})();