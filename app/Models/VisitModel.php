<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitModel extends Model
{
    protected $table = 'dt_visit';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ip_address', 'user_agent', 'visited_at'];
}
