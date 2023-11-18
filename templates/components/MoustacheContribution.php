<?php

namespace App\Components;

use App\Service\Interfaces\Component;

class MoustacheContribution extends Component
{
    public function render()
    { ?>
        <li class="contributions__line">
            <svg width="104" height="23" viewbox="0 0 104 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M19.2077 22.0892C33.927 24.4031 41.6501 20.9303 44.8707 17.311C46.7988 15.1442 45.0834 11.9956 42.1878 12.1618C38.8815 12.3515 35.7481 14.3067 33.8299 16.912C31.5935 19.9493 28.024 20.9387 26.5188 21.0538C9.14426 22.0202 1.93354 7.42059 0.5 0C1.53215 14.496 13.4019 20.7661 19.2077 22.0892Z"
                    fill="#28292D" />
                <path
                    d="M83.4882 22.2521C67.0938 24.5537 59.1505 21.1357 55.9119 17.526C53.9336 15.3209 55.7536 12.1372 58.7142 12.2429C62.3213 12.3716 65.7576 14.3648 67.8467 17.0368C70.239 20.0965 74.0573 21.0932 75.6675 21.2091C94.2532 22.1826 101.967 7.47532 103.5 0C102.396 14.603 89.6987 20.9193 83.4882 22.2521Z"
                    fill="#28292D" />
            </svg>
        </li>
<?php
    }
}
?>