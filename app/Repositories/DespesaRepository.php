<?php

namespace App\Repositories;

use App\Models\Despesa;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DespesaRepository
{
    private array $allowed = ['id', 'deputado_id'];

    public function applyFilters($query, array $filters = [])
    {
        foreach ($filters as $key => $value):
            if (!in_array($key, $this->allowed) or is_null($value)) continue;

            if (is_array($value)) {
                $query->whereIn($key, $value);

                continue;
            }

            if (is_null($value)) continue;

            match ($key) {
                'id' => $query->where('id', $value),
                'deputado_id' => $query->where('deputado_id', $value),
                default => $query->where($key, $value),
            };
        endforeach;

        return $query;
    }

    public function getAll(array $filters = [], $perPage = 10, $columns = [], $orderBy = '')
    {
        try {
            $query = Despesa::query();
            $query = $this->applyFilters($query, $filters);

            if (! $columns) $columns = ['*'];

            $data = $query->paginate($perPage, $columns, 'pagina');

            if (! $data->count()) throw new ModelNotFoundException('Not Found.', 404);

            return $data;
        } catch (ModelNotFoundException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getOne(array $filters = [], $columns = [])
    {
        try {
            $query = Despesa::query();
            $query = $this->applyFilters($query, $filters, $this->allowed);

            if (! $columns) $columns = ['*'];

            $query->select($columns);

            $data = $query->first();

            if (! $data) throw new ModelNotFoundException('Not Found.', 404);

            return $data;
        } catch (ModelNotFoundException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getById($id)
    {
        try {
            return Despesa::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $despesa = Despesa::updateOrCreate($data);

            DB::commit();

            return $despesa;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {
            $despesa = Despesa::findOrFail($id);
            $despesa->update($data);

            DB::commit();

            return $despesa;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();

            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $despesa = $this->findById($id);
            $despesa->delete();

            DB::commit();

            return $despesa ;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();

            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
