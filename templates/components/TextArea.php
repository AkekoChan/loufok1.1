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

<div class="modal__keepdata">
    <span>Votre brouillon a été enregistré.</span>
</div>

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

    .modal__keepdata {
        position: fixed;
        bottom: 3rem;
        left: 50%;
        opacity: 0;
        z-index: 19;
        pointer-events: none;
        font-size: 1.2rem;
        transform: translate(-50%, -50%);
        border: 2px solid var(--light-gray);
        background-color: var(--white);
        color: var(--black-1);
        padding: 1rem 2rem;
        border-radius: .0375rem;
        box-shadow: var(--box-shadow);
    }
</style>
<script defer>

let wordCounter = document.querySelector('.word-counter');
let textArea = document.querySelector('#textAreaInput');

            let storeID = document.getElementById('storeID')?.value;

            textArea.value = localStorage.getItem(storeID + "\\text") ?? "";
            console.log("Storage Retrieved");

function updateStorage() {
    if(!storeID) return;
    localStorage.setItem(storeID+"\\text", textArea.value);

    document.querySelector('.modal__keepdata').animate([
            { opacity: 0, transform: 'translate(-50%, -50%)' },
            { opacity: 1, transform: 'translate(-50%, -35%)' },
            { opacity: 0, transform: 'translate(-50%, 50%)' }
        ], {
            duration: 2400, // 3 secondes
            iterations: 1, // animation infinie
            fill: 'forwards',
            easing: 'ease-in-out' // type d'interpolation
        });
    // console.log(textArea.value);
    console.log("Storage Updated");
}

            // window.addEventListener('beforeunload', updateStorage);

            wordCounter.innerText = `${textArea.value.length}/280`;

            if (textArea.value.length < 50 || textArea.value.length > 280) textArea.setCustomValidity(
                "Le texte de contribution doit faire entre 50 et 280 caractères.");

            textArea.addEventListener('change', (evt) => {
                if (textArea.value.length < 50 || textArea.value.length > 280) {
                    textArea.setCustomValidity("Le texte de contribution doit faire entre 50 et 280 caractères.");
                } else {
                    textArea.setCustomValidity("");
                }
            });

            textArea.addEventListener('keypress', (evt) => {
                wordCounter.innerText = `${evt.target.value.length}/280`;
                if (textArea.value.length >= 50 && textArea.value.length <= 280) {
                    textArea.setCustomValidity("");
                }
            });

            textArea.addEventListener('keyup', (evt) => {
                wordCounter.innerText = `${evt.target.value.length}/280`;
                if (textArea.value.length >= 50 && textArea.value.length <= 280) {
                    textArea.setCustomValidity("");
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('keepData')?.addEventListener('click', updateStorage);
                document.querySelector('.form').addEventListener('submit', () => {
                    localStorage.removeItem(storeID + "\\text");
                });
            })
        </script>
<?php
    }
}
?>