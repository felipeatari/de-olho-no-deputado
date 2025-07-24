<?php

namespace App\Services;

use App\Repositories\DeputadoRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeputadoService extends Service
{
    public function __construct(
        protected DeputadoRepository $deputadoRepository
    )
    {
    }

    public function getAll(array $filter = [], $perPage = 10, array $columns = [])
    {
        try {
            $this->data = $this->deputadoRepository->getAll($filter, $perPage, $columns);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado(s) não encontrado(s).');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getOne(array $filter = [], array $columns = [])
    {
        try {
            $this->data = $this->deputadoRepository->getOne($filter, $columns);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getById(?int $id = null)
    {
        try {
            $this->data = $this->deputadoRepository->getById($id);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function create(array $data)
    {
        try {
            $this->data = $this->deputadoRepository->create($data);

            $this->code = 201;

            return $this;
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function update(?int $id = null, array $data = [])
    {
        try {
            $this->data = $this->deputadoRepository->update($id, $data);

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function delete(?int $id = null)
    {
        try {
            $this->deputadoRepository->delete($id);

            $this->data = true;

            return $this;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
