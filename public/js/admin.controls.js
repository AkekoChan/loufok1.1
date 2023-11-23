'use strict';
(function(){
    const Control = {
        $: {
            form: $('form.form'),
            contribution: $('.form__textarea')
        },
        init: () => {
            Control.$.form.addEventListener('submit', Control.submit);
        },
        submit: (evt) => {
            evt.preventDefault();
            let input = Control.$.contribution;
            let len = input.value.length

            if(len < 50 || len > 280) {
                return input.setCustomValidity("Le texte de contribution doit faire entre 50 et 280 caractères.");
            }

            evt.target.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', Control.init);
})();