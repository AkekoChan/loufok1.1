<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class Header extends Component
{
    public function render()
    {
?>
        <header class="header">
            <div class="header__profile">
                <img width="100px" src="https://api.dicebear.com/7.x/notionists-neutral/svg?seed=<?php echo $this->user->is_admin ? strtok($this->user->mail, "@") : $this->user->nom ?>&scale=200&radius=8&glassesProbability=60" alt="Photo de profil">
                <p>
                    Bienvenue, <br>
                    <span class="bolder"><?php echo $this->user->is_admin ? ucwords(strtok($this->user->mail, "@")) : $this->user->nom ?></span>
                </p>
            </div>

            <div class="header__notification">
                <div class="header__bell">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0); --darkreader-inline-fill: #e8e6e3;" data-darkreader-inline-fill="">
                        <path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z">
                        </path>
                    </svg>
                </div>
                <div class="header__popup">
                    <!-- <p class="header__popupSubtitle">Notifications</p> -->
                    <ul>
                        <li>Un cadavre exquis vient d'être ajouté !</li>
                        <li>Le dernière cadavre exquis vient de se terminer</li>
                    </ul>
                </div>
            </div>
        </header>
<?php
    }
}
?>