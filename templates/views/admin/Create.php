<?php

namespace App\Templates\Views\Admin;

use App\Components;
use App\Service\Interfaces\Template;

class Create extends Template
{
    public function render()
    {
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php $this->component(Components\Head::class); ?>
    <link rel="stylesheet" href="<?php $this->public("/css/pages/newCadavre.css"); ?>">
</head>

<body>
    <?php $this->component(Components\Header::class); ?>
    <main>
      <section class="new-cadaver">
        <div class="new-cadaver__info">
          <h1 class="new-cadaver__title bigger">
            Fabrication d’un cadavre exquis
          </h1>
          <p class="new-cadaver__description center">
            À vous de créer votre propre Cadavre Exquis et de partager le
            plaisir avec tout le monde !
          </p>
        </div>
        <form method="POST" class="new-cadaver__form form">
          <div class="new-cadaver__form-group">
            <h3 class="new-cadaver__title smaller">
              Remplissez les informations
            </h3>
            <input
              type="text"
              id="cadaver-title"
              class="new-cadaver__input form__input"
              name="cadaver-title"
              placeholder="Titre du cadavre exquis"
              required
            />

            <input
              type="number"
              min="1"
              value="1"
              id="contributions-count"
              class="new-cadaver__input form__input"
              name="contributions-count"
              placeholder="Nombre de contributions maxiumum"
              required
            />

            <div>
                <?php $this->component(Components\TextArea::class); ?>
            </div>
          </div>

          <div class="new-cadaver__form-group">
            <h3 class="new-cadaver__title smaller">La période de jeu</h3>
            <input type="date" name="date-start" id="dateStart">
            <input type="date" name="date-end" id="dateEnd">
            <!-- Calendrier personnalisé (à ajouter selon vos besoins) -->
          </div>

          <button type="submit" class="new-cadaver__submit-button btn-primary">
            Enregistrer le Cadavre Exquis
          </button>
        </form>
      </section>
    </main>
    <?php $this->component(Components\Footer::class, [ "current_page" => "home" ]); ?>
</body>

</html>
<?php
    }
}
?>