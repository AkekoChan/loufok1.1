<?php
    namespace App\Components;

    use App\Service\Interfaces\Component;

    class Quill extends Component {
        public function render ()
        {
            ?>
                <link href="<?php $this->public("/css/blocs/__quill.css"); ?>" rel="stylesheet">
                <script src="<?php $this->public("/js/__quill.js"); ?>"></script>
                <span class="madewith">Made with <a target="_blank" href="https://quilljs.com">Quill</a></span>
                <div class="editor__container">
                    <div id="editor"></div>
                </div>
                <style>
                    .editor__container {
                        filter: drop-shadow(0 2px 8px rgba(99, 99, 99, 0.2));
                    }
                    .word-counter {
                        border: 2px solid #caecab;
                        padding: .3rem;
                        border-radius: 5px;
                        transform: translate(-110%, -125%);
                        position: relative;
                        left: 100%;
                        display: block;
                        width: fit-content;
                    }
                    .ql-editor.ql-blank::before {
                        color: var(--gray) !important;
                        opacity: .54;
                        font-style: normal;
                    }
                    #editor {
                        background: var(--white);
                        padding-bottom: 2rem;
                        font-size: 1rem;
                        border-radius: 0.375rem;
                        border-top-left-radius: 0;
                        border-top-right-radius: 0;
                        max-height: 40vh;
                        overflow-y: auto;
                    }
                    .ql-toolbar, #editor {
                        border: 2px solid var(--light-gray) !important;
                    }
                    .ql-toolbar {
                        border-bottom: none !important;
                        background-color: var(--white);
                        border-radius: 0.375rem;
                        border-bottom-left-radius: 0;
                        border-bottom-right-radius: 0;
                    }
                    #editor.invalid ~ .word-counter {
                        border-color: #d85125;
                    }
                    form .madewith {
                        color: #333444;
                        padding: .2rem;
                        opacity: .6;
                        font-size: .9rem;
                        margin-bottom: 4px;
                        display: block;
                    }
                </style>
                <script>
                    const quill = new Quill('#editor', {
                        theme: 'snow',
                        placeholder: "Ecrivez la suite de ce Cadavre Exquis",
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline', 'strike'],
                                ['clean']
                            ]
                        }
                    });

                    const limit = 280;
                    const min = 50;
                    const Editor = document.querySelector('#editor');

                    Editor.insertAdjacentHTML("afterend", `<input required max="280" min="50" name='new_contribution' id='contribution' type='hidden'>`);
                    Editor.insertAdjacentHTML("afterend", `<span class="word-counter">0/${limit}</span>`);
                    Editor.classList.add('invalid');

                    quill.on('text-change', function (delta, old, source) {
                        if (quill.getLength() > limit) {
                            quill.deleteText(limit, quill.getLength());
                            Editor.classList.add('invalid');
                        } else if (quill.getLength() - 1 < min) {
                            Editor.classList.add('invalid');
                        } else {
                            Editor.classList.remove('invalid');
                        }
                        document.querySelector('#editor ~ .word-counter').innerText = `${quill.getLength() - 1}/${limit}`;
                    });

                    document.querySelector('form').addEventListener('submit', (evt) => {
                        evt.preventDefault();
                        if(quill.getLength() - 1 < min || quill.getLength() > limit) return;
                        document.querySelector('#contribution').value = quill.root.innerHTML;
                        evt.target.submit();
                    });
                </script>
            <?php
        }
    }
?>