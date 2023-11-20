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
                <div id="editor" class="form__textarea"></div>
                <style>
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
                    #editor.invalid ~ .word-counter {
                        border-color: #d85125;
                    }
                    form .madewith {
                        color: #333444;
                        padding: .2rem;
                        opacity: .6;
                        font-size: .9rem;
                    }
                </style>
                <script>
                    const quill = new Quill('#editor', {
                        theme: 'snow',
                        placeholder: "Ecrivez la suite de ce cadavre exquis",
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