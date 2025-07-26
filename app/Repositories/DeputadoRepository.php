<?php

namespace App\Repositories;

use App\Models\Deputado;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DeputadoRepository
{
    private array $allowed = ['id', 'nome', 'sigla_partido'];

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
                'nome' => $query->where('nome', 'like', "%$value%"),
                'id' => $query->where('id', $value),
                'sigla_partido' => $query->where('sigla_partido', $value),
                default => $query->where($key, $value),
            };
        endforeach;

        return $query->orderByDesc('nome');
    }

    public function getAll(array $filters = [], $perPage = 10, $columns = [])
    {
        try {
            $query = Deputado::query();
            $query = $this->applyFilters($query, $filters);

            if (! $columns) $columns = ['*'];

            $data = $query->paginate($perPage, $columns, 'pagina')->appends(request()->query());

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
            $query = Deputado::query();
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
            return Deputado::findOrFail($id);
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
            $deputado = Deputado::updateOrCreate($data);

            DB::commit();

            return $deputado;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {
            $deputado = Deputado::findOrFail($id);
            $deputado->update($data);

            DB::commit();

            return $deputado;
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
            $deputado = $this->getById($id);
            $deputado->delete();

            DB::commit();

            return $deputado ;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();

            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
