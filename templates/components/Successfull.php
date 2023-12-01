<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class Successfull extends Component
{
    public function render()
    {
?>
        <div id="notifications">
        </div>

        <style>
            #notifications {
                position: fixed;
                z-index: 960;
                top: 5%;
                left: 50%;
                transform: translateX(-50%);
            }

            .notification {
                background: white;
                border: 2px solid var(--light-gray);
                box-shadow: var(--box-shadow);
                width: 100%;
                position: relative;
                padding: 1rem 1.4rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-radius: 8px;
                transform: translateY(-150%);
                animation: slideIn 6s ease-in-out;
            }

            .notification::after {
                content: "";
                position: absolute;
                left: 0;
                bottom: 0;
                width: 0%;
                height: 6px;
                background-color: var(--dark-green);
            }

            .notification.progress::after {
                animation: progress var(--duration) ease-in-out forwards;
            }

            .notification__message {
                display: flex;
                align-items: center;
                gap: 1rem;
                text-transform: uppercase;
            }

            .notification__message a {
                text-decoration: underline;
            }

            .notification__close {
                padding: 8px;
                border: none;
            }

            .notification__close svg {
                width: 12px;
                height: 12px;
                display: flex;
            }

            @keyframes progress {
                0% {
                    width: 0%;
                }

                100% {
                    width: 100%;
                }
            }

            @keyframes slideIn {
                0% {
                    transform: translateY(-150%);
                    opacity: 0;
                }

                10%,
                60% {
                    transform: translateY(0);
                    opacity: 1;
                }

                100% {
                    transform: translateY(-100%);
                    opacity: 0;
                }
            }
        </style>
        <script defer>
            const svgSuccess = `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M24 4C12.972 4 4 12.972 4 24C4 35.028 12.972 44 24 44C35.028 44 44 35.028 44 24C44 12.972 35.028 4 24 4ZM24 40C15.178 40 8 32.822 8 24C8 15.178 15.178 8 24 8C32.822 8 40 15.178 40 24C40 32.822 32.822 40 24 40Z" fill="#91B174"/>
<path d="M19.998 27.174L15.4 22.584L12.576 25.416L20.002 32.826L33.414 19.414L30.586 16.586L19.998 27.174Z" fill="#91B174"/>
</svg>
`;

            create__notification(`${svgSuccess} <?php echo $this->success ?>`, true, 4000, undefined, '#notifications');


            // Notifications
            function create__notification(content = "", autoClose = true, duration = 5000, delay = 1000, parent =
                "#notifications") {
                let wrapper = document.querySelector(`${parent}`);
                if (wrapper) {

                    clear__notifications(wrapper);


                    let notification = document.createElement('div');
                    let message = document.createElement('div');
                    notification.classList.add('notification');
                    notification.classList.add('top-fade');
                    message.classList.add('notification__message');


                    notification.style.setProperty('--duration', `${duration}ms`);
                    message.innerHTML = content;


                    notification.appendChild(message);
                    wrapper.appendChild(notification);

                    if (autoClose) {

                        setTimeout(() => {
                            notification.classList.add('progress');
                        }, delay);


                        setTimeout(() => {
                            clear__notifications(wrapper);
                        }, duration + delay * 2)
                    } else {
                        let close = document.createElement('button');
                        close.classList.add('notification__close');
                        close.setAttribute('type', 'button');
                        close.addEventListener('click', () => clear__notifications(wrapper));

                        let icon =
                            '<svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path id="Union" fill-rule="evenodd" clip-rule="evenodd" d="M20.3771 21.532C20.7938 21.9487 21.4773 21.947 21.896 21.5284C22.3147 21.1097 22.3163 20.4262 21.8996 20.0095L12.748 10.8579L21.5319 2.07393C21.9486 1.65725 21.947 0.973761 21.5283 0.555079C21.1096 0.136397 20.4262 0.134768 20.0095 0.551456L11.2255 9.33541L2.20163 0.311533C1.78494 -0.105155 1.10146 -0.103525 0.682774 0.315156C0.264093 0.733838 0.262461 1.41732 0.679149 1.83401L9.70303 10.8579L0.31149 20.2494C-0.105198 20.6661 -0.103568 21.3496 0.315114 21.7683C0.733796 22.187 1.41728 22.1886 1.83397 21.7719L11.2255 12.3804L20.3771 21.532Z" fill="var(--color-primary, black)"/></svg>';
                        close.innerHTML = icon;

                        notification.appendChild(close);
                    }
                }

                function clear__notifications(parent) {
                    parent ? parent.innerHTML = '' : null;
                }
            }
        </script>
<?php
    }
}
?>