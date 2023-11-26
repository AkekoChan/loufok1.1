<?php
    namespace App\Models;
    
    use App\Service\Database\Model;
    use App\Entities;

    class ContributionModel extends Model {
        public string $table = "contribution";
        public string $entity = Entities\ContributionEntity::class;
    }
?>