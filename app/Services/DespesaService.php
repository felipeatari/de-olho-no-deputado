<?php

namespace App\Services;

use App\Repositories\DespesaRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DespesaService extends Service
{
    public function __construct(
        protected DespesaRepository $despesaRepository
    )
    {
    }

    protected function repository(): mixed
    {
        return $this->despesaRepository;
    }

    public function getAll(array $filter = [], $perPage = 10, array $columns = [])
    {
        try {
            $data = $this->despesaRepository->getAll($filter, $perPage, $columns);

            return $data;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa(s) não encontrada(s).');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getOne(array $filter = [], array $columns = [])
    {
        try {
            $despesa = $this->despesaRepository->getOne($filter, $columns);

            return $despesa;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getById(?int $id = null)
    {
        try {
            $despesa = $this->despesaRepository->getById($id);

            return $despesa;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function create(array $data)
    {
        try {
            $despesa = $this->despesaRepository->create($data);
            $this->code = 201;

            return $despesa;
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function update(?int $id = null, array $data = [])
    {
        try {
            $despesa = $this->despesaRepository->update($id, $data);

            return $despesa;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function delete(?int $id = null)
    {
        try {
            $this->despesaRepository->delete($id);

            return true;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
