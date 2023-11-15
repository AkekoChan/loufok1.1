<?php
    namespace App\Models;

    use App\Models\Entities\RandContributionEntity;
    use App\Service\Database\Model;

    class RandContributionModel extends Model {
        public string $table = "randcontribution";
        public string $entity = RandContributionEntity::class;
    }
?>