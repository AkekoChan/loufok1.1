<?php
    namespace App\Models;

    use App\Models\Entities\ContributionEntity;
    use App\Service\Database\Model;

    class ContributionModel extends Model {
        public string $table = "contribution";
        public string $entity = ContributionEntity::class;
    }
?>