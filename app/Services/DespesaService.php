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

    public function getAll(array $filter = [], $perPage = 10, array $columns = [])
    {
        try {
            $this->data = $this->despesaRepository->getAll($filter, $perPage, $columns);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa(s) não encontrada(s).');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getOne(array $filter = [], array $columns = [])
    {
        try {
            $this->data = $this->despesaRepository->getOne($filter, $columns);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getById(?int $id = null)
    {
        try {
            $this->data = $this->despesaRepository->getById($id);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function create(array $data)
    {
        try {
            $this->data = $this->despesaRepository->create($data);

            $this->code = 201;

            return $despesa;
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function update(?int $id = null, array $data = [])
    {
        try {
            $this->data = $this->despesaRepository->update($id, $data);

            return $this;
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

            $this->data = true;

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Despesa não encontrada.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
