<?php

namespace App\Templates\Views;

use App\Components;
use App\Service\Interfaces\Template;

class Cadavre extends Template
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
              Explorez cet ancien Cadavre Exquis
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
              <?php $periode = $this->cadavre->periode->getConvertedPeriode() ?>
              Période de Jeu : <span class="bolder"><?php echo $periode["start"] ?></span> au <span class="bolder"><?php echo $periode["end"] ?></span>
            </p>
            <ul class="user-cadavers__list">
              <!-- Premier Contributions -->
              <?php foreach ($this->contributions as $contribution) : ?>
                <li class="user-cadavers__item bottom-fade <?php if ($contribution->id_contribution === $this->user_contribution->id_contribution) echo "owner" ?>">
                  <p class="user-cadavers__text">
                    <?php echo $contribution->text ?>
                  </p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="user-cadavers__thanks">
            <h3 class="user-cadavers__title smaller bottom-fade">
              Remerciements aux particpants
            </h3>
            <ul class="user-cadavers__tanks-list">
              <!-- Premier user -->
              <?php foreach ($this->contributors as $contributor) : ?>
                <li class="user-cadavers__tanks-item bottom-fade">
                  <img width="100px" src="https://api.dicebear.com/7.x/notionists-neutral/svg?seed=<?php echo $contributor->is_admin ? strtok($contributor->mail, "@") : $contributor->nom ?>&scale=200&radius=8&glassesProbability=60" alt="Photo de profil" />
                  <span><?php echo $contributor->is_admin ? ucwords(strtok($contributor->mail, "@")) : $contributor->nom ?></span>
                </li>
              <?php endforeach ?>
            </ul>
          </div>
        </section>
      </main>
      <?php if($this->success !== null) $this->component(Components\Successfull::class); ?>
      <?php $this->component(Components\Footer::class, ["current_page" => "lastCadavre"]); ?>
    </body>

    </html>
<?php
  }
}
?>