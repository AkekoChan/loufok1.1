<?php
    namespace App\Templates\Errors;

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
                    Error <?php echo $this->code; ?> | <?php echo $this->message; ?>
                </body>
            </html>
            <?php
        }
    }
?>