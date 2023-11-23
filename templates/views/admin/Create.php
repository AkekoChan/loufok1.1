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
          <div class="new-cadaver__info right-fade">
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
              <h3 class="new-cadaver__title smaller bottom-fade">
                Remplissez les informations
              </h3>

              <?php 
              if($this->error !== null) {
                $error_text = [
                  "2004" => "Veuillez compléter tout les champs. (". ($_GET['field'] ?? '?') .")",
                  "3001" => "Le nombre de contributions doit être supérieur ou égal à 1.",
                  "3002" => "Le texte de contribution doit faire entre 50 et 280 caractères.",
                  "3003" => "La date de fin ne peut pas être plus petite que la date de début.",
                  "3004" => "La période chevauche une autre période.",
                  "3005" => "Une erreur est survenue lors de la création.",
                  "23000" => "Un cadavre avec ce même titre existe déjà.",
                ][$this->error] ?? "Une erreur est survenue.";
                $this->component(Components\FormError::class, ["error_text" => $error_text]); 
              }
            ?>

              <input type="text" id="cadaver-title" class="new-cadaver__input form__input bottom-fade" name="cadaver-title" placeholder="Titre du cadavre exquis" required />

              <input type="number" min="1" value="1" id="contributions-count" class="new-cadaver__input form__input bottom-fade" name="contributions-count" placeholder="Nombre de contributions maxiumum" required />

              <div class="bottom-fade">
                <?php $this->component(Components\TextArea::class); ?>
              </div>
            </div>

            <div class="new-cadaver__form-group">
              <h3 class="new-cadaver__title smaller">La période de jeu</h3>
              <input type="date" name="date-start" id="dateStart">
              <input type="date" name="date-end" id="dateEnd">
              <!-- Calendrier personnalisé (à ajouter selon vos besoins) -->
            </div>

            <button type="submit" class="new-cadaver__submit-button btn-primary btn pop-in">
              Enregistrer le Cadavre Exquis
            </button>
          </form>
        </section>
      </main>
      <?php $this->component(Components\Footer::class, ["current_page" => "home"]); ?>
    </body>

    </html>
<?php
  }
}
?>