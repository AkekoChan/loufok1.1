<?php

namespace App\Templates\Views;

use App\Components;
use App\Service\Interfaces\Template;

class NoLastCadavre extends Template
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
              Aucun Cadavre Exquis à afficher
            </h2>
            <p class="user-cadavers__info-description center">
              Essayez de participer à un Cadavre Exquis !
            </p>
          </div>
      </main>
      <?php $this->component(Components\Footer::class, ["current_page" => "lastCadavre"]); ?>
    </body>

    </html>
<?php
  }
}
?>