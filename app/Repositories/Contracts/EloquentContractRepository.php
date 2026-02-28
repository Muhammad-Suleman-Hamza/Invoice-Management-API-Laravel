<?php

namespace App\Repositories\Contracts;

use App\Models\Contract;

class EloquentContractRepository implements ContractRepositoryInterface
{
    public function findById(int $id): Contract
    {
        return Contract::findOrFail($id);
    }
}
