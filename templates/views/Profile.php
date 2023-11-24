<?php

namespace App\Templates\Views;

use App\Components;
use App\Service\Interfaces\Template;

class Profile extends Template
{
  public function render()
  {
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
      <?php $this->component(Components\Head::class); ?>
      <link rel="stylesheet" href="/loufok/css/pages/profile.css" />
    </head>

    <body>
      <?php $this->component(Components\Header::class); ?>
      <main>
        <section class="profile">
          <!-- Première div pour les informations de profil -->
          <div class="profile__info">
            <h1 class="profile__title bigger bottom-fade">Votre Profil</h1>

            <!-- Photo de profil et nom de l'utilisateur -->
            <div class="profile__user bottom-fade">
              <img src="https://api.dicebear.com/7.x/notionists-neutral/svg?seed=<?php echo $this->user->is_admin ? strtok($this->user->mail, "@") : $this->user->nom ?>&scale=200&radius=8&glassesProbability=60" alt="Photo de profil" class="profile__photo" />
              <p class="profile__username smaller bolder">
                <?php echo $this->user->is_admin ? ucwords(strtok($this->user->mail, "@")) : $this->user->nom ?>
              </p>
            </div>

            <!-- Deux stats -->
            <div class="profile__stats bottom-fade">
              <p class="profile__stat">
                <span class="bolder-green">
                  <?php echo count($this->contributions) ?>
                </span>Nombre total <br />de contributions
              </p>
              <p class="profile__stat">
                <span class="bolder-green">
                  <?php echo round(array_sum(array_map(fn ($r) => strlen($r), array_column($this->contributions, "text"))) / max(1, count($this->contributions))) ?>
                </span>Nombre moyen <br />de caractères
              </p>
            </div>

            <!-- Bouton de déconnexion -->
            <a href="/loufok/logout" class="profile__logout btn-primary pop-in btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);">
                <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                <path d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z">
                </path>
              </svg>
              Déconnexion
            </a>
          </div>

          <?php if(count($this->contributions) > 0): ?>
          <!-- Deuxième div pour les anciens contributions -->
          <div class="profile__old-contributions">
            <h3 class="profile__old-contributions-title smaller bottom-fade">
              Vos dernières contributions
            </h3>

            <!-- Liste des anciens cadavres -->
            <ul class="profile__contributions-list">
              <?php foreach ($this->contributions as $contribution) : ?>
                <li class="profile__contributions-item bottom-fade">
                  <p class="profile__contributions-title" style="margin-bottom: 1rem;">
                    <svg width="58" height="15" viewBox="0 0 104 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19.2077 22.0892C33.927 24.4031 41.6501 20.9303 44.8707 17.311C46.7988 15.1442 45.0834 11.9956 42.1878 12.1618C38.8815 12.3515 35.7481 14.3067 33.8299 16.912C31.5935 19.9493 28.024 20.9387 26.5188 21.0538C9.14426 22.0202 1.93354 7.42059 0.5 0C1.53215 14.496 13.4019 20.7661 19.2077 22.0892Z" fill="#28292D" />
                      <path d="M83.4882 22.2521C67.0938 24.5537 59.1505 21.1357 55.9119 17.526C53.9336 15.3209 55.7536 12.1372 58.7142 12.2429C62.3213 12.3716 65.7576 14.3648 67.8467 17.0368C70.239 20.0965 74.0573 21.0932 75.6675 21.2091C94.2532 22.1826 101.967 7.47532 103.5 0C102.396 14.603 89.6987 20.9193 83.4882 22.2521Z" fill="#28292D" />
                    </svg>
                    <?php echo $contribution->cadavre_title ?>
                  </p>
                  <p class="profile__contributions-text">
                    <?php echo $contribution->text ?>
                  </p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
        </section>
      </main>
      <?php $this->component(Components\Footer::class, ["current_page" => "profile"]); ?>
    </body>

    </html>
<?php
  }
}
?>