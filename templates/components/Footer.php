<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class Footer extends Component
{
    public function render()
    {
?>
<footer class="footer">
    <nav>
        <ul class="footer__list">
            <li class="footer__item <?php echo $this->current_page === "home" ? "current" : ""; ?>">
                <!-- Mettre la class current au lien + span pour faire l'active de la page en cours -->
                <a href="/loufok" aria-label="Page d'accueil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="fill: rgb(0, 0, 0);">
                        <path
                            d="M12.71 2.29a1 1 0 0 0-1.42 0l-9 9a1 1 0 0 0 0 1.42A1 1 0 0 0 3 13h1v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7h1a1 1 0 0 0 1-1 1 1 0 0 0-.29-.71zM6 20v-9.59l6-6 6 6V20z">
                        </path>
                    </svg>
                    <span aria-hidden="true">Accueil</span>
                </a>
            </li>
            <li class="footer__item <?php echo $this->current_page === "profile" ? "current" : ""; ?>">
                <a href="/loufok/profile" aria-label="Page profil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="fill: rgb(0, 0, 0); --darkreader-inline-fill: #e8e6e3;" data-darkreader-inline-fill="">
                        <path
                            d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z">
                        </path>
                    </svg>
                    <span aria-hidden="true">Profil</span>
                </a>
            </li>
            <li class="footer__item <?php echo $this->current_page === "collection" ? "current" : ""; ?>">
                <a href="/loufok/collection" aria-label="Page des anciens cadavres exquis">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="fill: rgb(0, 0, 0); --darkreader-inline-fill: #e8e6e3;" data-darkreader-inline-fill="">
                        <path
                            d="M19 2.01H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.998 5 19.815 5 19.01c0-.101.009-.191.024-.273.112-.575.583-.717.987-.727H20c.018 0 .031-.009.049-.01H21V4.01c0-1.103-.897-2-2-2zm0 14H5v-11c0-.806.55-.988 1-1h7v7l2-1 2 1v-7h2v12z">
                        </path>
                    </svg>
                    <span aria-hidden="true">Ancien <br> cadavre</span>
                </a>
            </li>
        </ul>
    </nav>
</footer>
<?php
    }
}
?>