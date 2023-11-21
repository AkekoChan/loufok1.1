<?php

namespace App\Templates\Views;

use App\Components;
use App\Service\Interfaces\Template;

class Collection extends Template
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
        <div class="user-cadavers__info">
          <h2 class="user-cadavers__info-title bigger">
            Consulter ses anciens cadavres exquis
          </h2>
          <p class="user-cadavers__info-description center">
            Explorez tous les cadavres exquis auxquels vous avez pu participer !
          </p>
        </div>

        <ul class="user-cadavers__list">
          <?php foreach($this->user_cadavres as $cadavre): ?>
          <li class="user-cadavers__item">
            <div class="user-cadavers__item-content">
              <svg class="user-cadavers__item-svg" width="27" height="24" viewBox="0 0 27 24" fill="none" xmlns="http://www.w3.org/2000/svg" ><path d="M13 2C7.486 2 3 6.486 3 12C3 17.514 7.486 22 13 22C18.514 22 23 17.514 23 12C23 6.486 18.514 2 13 2ZM13 20C8.589 20 5 16.411 5 12C5 7.589 8.589 4 13 4C17.411 4 21 7.589 21 12C21 16.411 17.411 20 13 20Z" fill="#28292D" /><path d="M9.5 11C10.3284 11 11 10.3284 11 9.5C11 8.67157 10.3284 8 9.5 8C8.67157 8 8 8.67157 8 9.5C8 10.3284 8.67157 11 9.5 11Z" fill="#28292D" /><path d="M16.493 10.986C17.3176 10.986 17.986 10.3176 17.986 9.493C17.986 8.66844 17.3176 8 16.493 8C15.6684 8 15 8.66844 15 9.493C15 10.3176 15.6684 10.986 16.493 10.986Z" fill="#28292D" /><path d="M5.26021 15.7055C7.82276 16.2701 9.63117 15.9605 10.8519 15.3041C13.06 14.1169 11.0313 11.1493 9.63153 13.2292C9.5381 13.368 9.45116 13.5138 9.37165 13.6651C8.74284 14.8622 7.73917 15.2521 7.31593 15.2974C2.43058 15.6783 0.403082 9.9245 0 7C0.290219 12.713 3.62773 15.1841 5.26021 15.7055Z" fill="#28292D" /><path d="M21.7398 15.7074C19.0923 16.2607 17.2832 15.9692 16.0843 15.3346C13.8687 14.1618 15.9684 11.1503 17.3682 13.2299C17.4617 13.3689 17.5488 13.515 17.6284 13.6666C18.2572 14.8638 19.2609 15.2538 19.6841 15.2992C24.5694 15.6801 26.5969 9.92513 27 7C26.7098 12.7142 23.3723 15.1858 21.7398 15.7074Z" fill="#28292D" /></svg>
              <p class="user-cadavers__item-title"><?php echo $cadavre->title ?></p>
            </div>
            <p class="user-cadavers__item-period">
              <?php $periode = $cadavre->periode->getConvertedPeriode() ?>
              Période de Jeu : <span class="bolder"><?php echo $periode["start"] ?></span> au <span class="bolder"><?php echo $periode["end"] ?></span>
            </p>
            <a href="/loufok/cadavre/<?php echo $cadavre->id_cadavre_exquis ?>" class="user-cadavers__item-link btn-secondary">
              Voir ce cadavre exquis
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </section>
    </main>
    <?php $this->component(Components\Footer::class, [ "current_page" => "collection" ]); ?>
</body>

</html>
<?php
    }
}
?>