<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class TextArea extends Component
{
    public function render()
    {
?>
<textarea id="textAreaInput" spellcheck="true" minlength="50" maxlength="280" required class="form__textarea"
    name="contribution" placeholder="Ecrivez votre contribution" role="textarea"
    aria-label="Ecrivez votre contribution"></textarea>
<span class="word-counter" role="count word">0/280</span>

<style>
    #textAreaInput {
        height: 100%;
        padding-bottom: 3rem;
        font-size: 1rem;
        max-height: 40vh;
        overflow-y: auto;
    }

    #textAreaInput:valid+.word-counter {
        border-color: #caecab;
    }

    .word-counter {
        background: var(--white);
        border: 2px solid #d85125;
        padding: .3rem;
        border-radius: 5px;
        transform: translate(-110%, -125%);
        position: relative;
        left: 100%;
        display: block;
        width: fit-content;
    }
</style>
<script defer>
let timeout; // brouillon traitement

let cadavreId = document.querySelector('#currentCadavreId')?.value ?? "adminContrib";

let wordCounter = document.querySelector('.word-counter');
let textArea = document.querySelector('#textAreaInput');

// textArea.setCustomValidity("Wrong");
textArea.value = localStorage.getItem(cadavreId) ?? "";
console.log("Storage Retrieved");

function updateStorage() {
    localStorage.setItem(cadavreId, textArea.value);
    console.log(textArea.value);
    console.log("Storage Updated");
}

wordCounter.innerText = `${textArea.value.length}/280`;

if(textArea.value.length < 50 || textArea.value.length > 280) textArea.setCustomValidity("Le texte de contribution doit faire entre 50 et 280 caractères.");

window.addEventListener('beforeunload', updateStorage);

textArea.addEventListener('keypress', (evt) => {
    wordCounter.innerText = `${evt.target.value.length}/280`;
    if(textArea.value.length >= 50 && textArea.value.length < 280) {
        textArea.setCustomValidity("");
    } else {
        textArea.setCustomValidity("Le texte de contribution doit faire entre 50 et 280 caractères.");
    }
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(updateStorage, 1800);
});

textArea.addEventListener('keyup', (evt) => {
    wordCounter.innerText = `${evt.target.value.length}/280`;
    if(textArea.value.length >= 50 && textArea.value.length < 280) {
        textArea.setCustomValidity("");
    } else {
        textArea.setCustomValidity("Le texte de contribution doit faire entre 50 et 280 caractères.");
    }
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(updateStorage, 1800);
});
</script>
<?php
    }
}
?>