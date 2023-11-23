<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class Head extends Component
{
    public function render()
    {
?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- <link rel="preload" href="<?php $this->public("/assets/favicon.svg") ?>" as="image" type="image/svg+xml"> -->
        <link rel="icon" href="<?php $this->public("/assets/el-moustacho.svg") ?>" type="image/svg+xml">
        <link rel="icon" href="<?php $this->public("/assets/favicon.png") ?>" type="image/png">
        <link rel="shortcut icon" href="<?php $this->public("/assets/el-moustacho.svg") ?>" type="image/svg+xml">
        <link rel="shortcut icon" href="<?php $this->public("/assets/favicon.png") ?>" type="image/png">
        <link rel="stylesheet" href="<?php $this->public("/css/main-min.css", "/css/main.css") ?>">
        <script src="<?php $this->public("/js/utility.js") ?>" defer></script>
        <script src="<?php $this->public("/js/gsap.min.js") ?>" defer></script>
        <script src="<?php $this->public("/js/main-min.js", "/js/main.js") ?>" defer></script>
        <!-- <script src="<?php // $this->public("/js/sw.js") ?>" defer></script> -->
        <?php echo $this->head ?>
        <title><?php echo $this->title ?? ENV->NAME ?></title>
<?php
    }
}
?>