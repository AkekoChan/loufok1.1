<?php
    namespace App\Components;

    use App\Service\Interfaces\Component;

    class FormError extends Component {
        public function render ()
        {
            ?>
                <div>
                    <?php echo $this->error_text; ?>
                </div>
            <?php
        }
    }
?>