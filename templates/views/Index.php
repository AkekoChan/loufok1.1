<?php

namespace App\Templates\Views;

use App\Components;
use App\Service\Interfaces\Component;
use App\Service\Interfaces\Template;

class Index extends Template
{
    public function render()
    {
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php $this->component(Components\Head::class); ?>
    <link rel="stylesheet" href="<?php $this->public("/css/pages/currentCadavre.css"); ?>">
</head>

<body>
    <?php $this->component(Components\Header::class); ?>
    <main>

        <section class="currentCadavre-container">

            <div class="currentCadavre__info">
                <h1 class="bigger">Contribuer à votre manière</h1>
                <p class="center">Les petites <span class="bolder-green">moustaches</span> symbolisent les autres
                    contributions. A vous d’ajouter
                    votre petite pierre à l’édifice!
                </p>
            </div>

            <div class="currentCadavre__details">
                <h3 class="smaller"><?php echo $this->cadavre->title; ?></h3>
                <!-- Gérer le s si 0/1 ou plus -->
                <p class="contributions-remaining">Nombre de contributions restantes : 
                    <span class="bolder-green">
                        <?php echo $this->cadavre->remaining_contributions; ?>
                    </span>
                </p>
                <p class="periods">Vous pouvez participer du <span class="bolder"><?php echo $this->periode["start"]; ?></span> au <span
                        class="bolder"><?php echo $this->periode["end"]; ?></span> (<span class="bolder-green"><?php echo $this->remaining_days; ?></span> <?php echo $this->remaining_days > 1 ? " jours restants" : " jour restant" ?>)</p>
            </div>
            <ul class="contributions__list">
                <?php for ($i=1; $i < $this->cadavre->contributions + 1; $i++) { 
                    if($this->random_contribution->submission_order == $i) {
                        echo '<li class="contributions__item">
                            <p>'. $this->random_contribution->text .'</p>
                        </li>';
                    } else {
                        $this->component(Components\MoustacheContribution::class);
                    }
                } ?>
            </ul>

            <form class="form" method="" action="">
                <h3 class="smaller center">A votre tour de jouer !</h3>
                <?php $this->component(Components\Quill::class); ?>
                <button class="btn-primary" type="submit">Soumettre votre cadavre exquis</button>
            </form>

        </section>
    </main>
    <?php $this->component(Components\Footer::class); ?>
</body>

</html>
<?php
    }
}
?>