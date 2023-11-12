<?php
    namespace App\Models;

    use App\Models\Entities\CadavreExquisEntity;
    use App\Service\Database\Model;

    class CadavreExquisModel extends Model {
        public string $table = "cadavre_exquis";
        public string $entity = CadavreExquisEntity::class;
    }
?>