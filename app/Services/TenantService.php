<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\UnauthorizedScopeException;
use App\Http\Resources\TenantResource;
use App\Http\Utilities\ServiceResponse;
use App\Models\Tenant;
use App\Repositories\TenantRepository;
use App\Traits\HandleImageTrait;
use App\Traits\HelperTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class TenantService
{
    use HelperTrait;
    use HandleImageTrait;

    public function __construct(protected TenantRepository $repository)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function index(array $data): ServiceResponse
    {
        try {
            $result      = $this->repository->index($data);
            $message     = $result->total() > 0 ? 'Registros encontrados' : 'Nenhum registro encontrado';
            $resultItems = TenantResource::collection($result);
            $result      = $this->toPaginator($result, $resultItems);

            return ServiceResponse::success($result, $message);
        } catch (Throwable $throwable) {
            return ServiceResponse::error($throwable);
        }
    }

    /**
     * @throws Throwable
     * @param array<string, mixed> $data
     */
    public function store(array $data, ?UploadedFile $logo = null): ServiceResponse
    {
        try {
            DB::beginTransaction();
            $tenant = Tenant::create($data);

            if ($logo instanceof UploadedFile) {
                $path     = sprintf('public/customer/%s/logos', $tenant->id);
                $logoPath = $this->saveImage($logo, $path, true);
                $tenant->update(['logo' => $logoPath]);
            }

            DB::commit();

            return ServiceResponse::success($tenant, message: 'Tenant criado com sucesso!', status: 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return ServiceResponse::error($e, 404, 'Tenant não encontrado.');
        } catch (Exception $e) {
            DB::rollBack();

            return ServiceResponse::error($e, message: $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     * @param array<string, mixed> $data
     */
    public function update(array $data, ?UploadedFile $logo, int $id): ServiceResponse
    {
        try {
            DB::beginTransaction();
            $tenant = Tenant::findOrFail($id);

            if ($logo instanceof UploadedFile) {
                $path         = sprintf('public/customer/%s/logos', $tenant->id);
                $data['logo'] = $this->saveImage($logo, $path, true);
            }

            if (Auth::user()->tenant_id == $tenant->id && isset($data['status']) && $data['status'] === 'inactive') {
                throw new UnauthorizedScopeException('
                    Não é possível inativar o tenant em que o usuário autenticado está associado.
                ');
            }

            $tenant->update($data);

            DB::commit();

            return ServiceResponse::success(Tenant::find($id), message: 'Tenant atualizado com sucesso!');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return ServiceResponse::error($e, 404, 'Tenant não encontrado.');
        } catch (UnauthorizedScopeException $e) {
            DB::rollBack();

            return ServiceResponse::error($e, 403, message: $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();

            return ServiceResponse::error($e, message: $e->getMessage());
        }
    }
}
