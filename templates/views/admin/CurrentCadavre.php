<?php

namespace App\Templates\Views\Admin;

use App\Components;
use App\Service\Interfaces\Template;

class CurrentCadavre extends Template
{
  public function render()
  {
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
      <?php $this->component(Components\Head::class); ?>
      <link rel="stylesheet" href="/loufok/css/pages/oldCadavre.css" />
    </head>

    <body>
      <?php $this->component(Components\Header::class); ?>
      <main>
        <section class="user-cadavers">
          <div class="user-cadavers__info-bis right-fade">
            <h2 class="user-cadavers__info-title bigger">
              Consultez l'avancement de ce Cadavre Exquis
            </h2>
            <p class="user-cadavers__info-description center">
              Découvrez les créations des autres participants de ce cadavre
              exquis.
            </p>
          </div>

          <div class="user-cadavers__wrapper">
            <h3 class="user-cadavers__title smaller bottom-fade">
              <?php echo $this->cadavre->title; ?>
            </h3>
            <p class="user-cadavers__period bottom-fade">
              <?php $periode = $this->cadavre->periode->getConvertedPeriode(); $diff = $this->cadavre->periode->getDiff(); ?>
              Période de Jeu : <span class="bolder"><?php echo $periode["start"] ?></span> au <span class="bolder"><?php echo $periode["end"] ?></span>
              <span><?= $diff > 1 ? "($diff jours restants)" : "($diff jour restant)" ?></span>
            </p>
            <ul class="user-cadavers__list">
              <!-- Premier Contributions -->
              <?php foreach ($this->cadavre->contributions as $contribution) : ?>
              <?php $user = $contribution->getUser() ?>
                <li class="user-cadavers__item bottom-fade <?php if ($this->user_contribution !== null && $contribution->id_contribution === $this->user_contribution->id_contribution) echo "owner" ?>">
                  <div style="margin: 0; gap: 1rem" class="user-cadavers__tanks-item bottom-fade">
                    <img width="100px" src="https://api.dicebear.com/7.x/notionists-neutral/svg?seed=<?php echo $user->is_admin ? strtok($user->mail, "@") : $user->nom ?>&scale=200&radius=8&glassesProbability=60" alt="Photo de profil" />
                    <span><?php echo $user->is_admin ? ucwords(strtok($user->mail, "@")) : $user->nom ?></span>
                  </div>
                  <p class="user-cadavers__text">
                    <?php echo $contribution->text ?>
                  </p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </section>
      </main>
      <?php $this->component(Components\Footer::class, ["current_page" => "home"]); ?>
    </body>

    </html>
<?php
  }
}
?>