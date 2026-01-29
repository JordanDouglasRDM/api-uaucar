<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TenantRepository
{

    public function __construct(protected Tenant $model)
    {
    }

//    'id',
//        'domain',
//        'name',
//        'status',
//        'cnpj',
//        'logo',
//        'administrator_email',
//        'administrator_phone',
//        'responsible_name',
//        'responsible_email',
//        'responsible_phone',
//        'options',
//        'created_at',
//        'updated_at',

    public function index(array $data): LengthAwarePaginator
    {
        $query = $this->model->query();

        $filters = $data['filters'] ?? [];

        $query->withCount('users');

        $query->when(isset($filters['status']), function ($q) use ($filters): void {
            $q->where('status', $filters['status']);
        });

        $query->when(isset($filters['search']), function ($q1) use ($filters): void {
            $q1
                ->where('domain', 'like', '%' . $filters['search'] . '%')
                ->orWhere('cnpj', 'like', '%' . $filters['search'] . '%')
                ->orWhere('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('responsible_name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('responsible_email', 'like', '%' . $filters['search'] . '%')
                ->orWhere('responsible_phone', 'like', '%' . $filters['search'] . '%')
                ->orWhere('created_at', 'like', '%' . $filters['search'] . '%')
                ->orWhere('updated_at', 'like', '%' . $filters['search'] . '%');
        });

        return $query
            ->orderBy($data['order_by'], $data['order_direction'])
            ->paginate($data['per_page'])
            ->onEachSide(1);
    }

}