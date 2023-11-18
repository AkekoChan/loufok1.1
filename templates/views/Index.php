<?php
    namespace App\Templates\Views;

    use App\Components;
    use App\Service\Interfaces\Template;

    class Index extends Template {
        public function render () {
            ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->component(Components\Head::class); ?>
</head>

<body>
    <main>
        <?php $this->component(Components\Header::class); ?>
        Index template !
    </main>
</body>

</html>
<?php
        }
    }
?>