<?php
/**
 * Created by PhpStorm.
 * User: Denis Kostaev
 * Date: 17/02/2024
 * Time: 11:35
 */

namespace App\Repositories;

use App\Models\BankBranch;

class BankBranchRepository extends BaseRepository
{
    public function __construct(BankBranch $bankBranch)
    {
        parent::__construct($bankBranch);
    }

}
