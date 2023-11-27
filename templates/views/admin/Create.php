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
      <link rel="stylesheet" href="<?php $this->public("/css/blocs/calendar-min.css", "/css/blocs/calendar.css"); ?>">
      <script src="/loufok/js/admin.controls.js" defer></script>
      <script src="<?php $this->public("/js/calendar-min.js", "/js/calendar.js"); ?>" defer></script>
    </head>

    <body>
      <?php $this->component(Components\Header::class); ?>
      <main>
        <section class="new-cadaver">
          <div class="new-cadaver__info right-fade">
            <h1 class="new-cadaver__title bigger">
              Fabrication d’un Cadavre Exquis
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
              if ($this->error !== null) {
                $error_text = [
                  "2004" => "Veuillez compléter tout les champs. (" . ($_GET['field'] ?? '?') . ")",
                  "3001" => "Le nombre de contributions doit être supérieur ou égal à 1.",
                  "3002" => "Le texte de contribution doit faire entre 50 et 280 caractères.",
                  "3003" => "La date de fin ne peut pas être plus petite que la date de début.",
                  "3004" => "La période chevauche une autre période.",
                  "3005" => "Une erreur est survenue lors de la création.",
                  "3008" => "La date de début ne peut pas être inférieur à la date d'aujourd'hui.",
                  "23000" => "Un cadavre avec ce même titre existe déjà.",
                ][$this->error] ?? $this->error ?? "Une erreur est survenue.";
                $this->component(Components\FormError::class, ["error_text" => $error_text]);
              }
              ?>

              <input type="text" id="cadaver-title" class="new-cadaver__input form__input bottom-fade" name="cadaver-title" placeholder="Titre du Cadavre Exquis" required />

              <input type="number" id="contributions-count" class="new-cadaver__input form__input bottom-fade" name="contributions-count" min="1" placeholder="Nombre de contributions maxiumum" required />

              <div class="bottom-fade">
                <?php $this->component(Components\TextArea::class); ?>
              </div>
            </div>

            <div class="new-cadaver__form-group">
              <h3 class="new-cadaver__title smaller">La période de jeu</h3>
              <div class="js-calendar calendar">
                <input required tabindex="-1" type="date" name="date-start" id="dateStart">
                <input required tabindex="-1" type="date" name="date-end" id="dateEnd">
                <div class="calendar__head">
                  <div class="calendar__head--container">
                    <i tabindex="0" role="button" class="calendar__head--switch switch__left" data-switch="1"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M18.6 11.2l-12-9A1 1 0 005 3v18a1 1 0 00.55.89 1 1 0 001-.09l12-9a1 1 0 000-1.6z" />
                      </svg></i>
                    <span class="calendar__head--date">Janvier 1970</span>
                    <i tabindex="0" role="button" class="calendar__head--switch switch__right" data-switch="-1"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M18.6 11.2l-12-9A1 1 0 005 3v18a1 1 0 00.55.89 1 1 0 001-.09l12-9a1 1 0 000-1.6z" />
                      </svg></i>
                  </div>
                  <div class="calendar__head--days">
                    <span>Lun</span>
                    <span>Mar</span>
                    <span>Mer</span>
                    <span>Jeu</span>
                    <span>Ven</span>
                    <span>Sam</span>
                    <span>Dim</span>
                  </div>
                </div>
                <div class="calendar__body"></div>
              </div>
            </div>

            <button type="submit" class="new-cadaver__submit-button btn-primary btn pop-in">
              Enregistrer le Cadavre Exquis
            </button>
            <button class="btn-third btn pop-in">Enregistrer votre brouillon</button>
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