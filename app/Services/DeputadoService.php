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

    protected function repository(): mixed
    {
        return $this->deputadoRepository;
    }

    public function getAll(array $filter = [], $perPage = 10, array $columns = [])
    {
        try {
            $data = $this->deputadoRepository->getAll($filter, $perPage, $columns);

            return $data;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado(s) não encontrado(s).');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getOne(array $filter = [], array $columns = [])
    {
        try {
            $deputado = $this->deputadoRepository->getOne($filter, $columns);

            return $deputado;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function getById(?int $id = null)
    {
        try {
            $deputado = $this->deputadoRepository->getById($id);

            return $deputado;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function create(array $data)
    {
        try {
            $deputado = $this->deputadoRepository->create($data);
            $this->code = 201;

            return $deputado;
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function update(?int $id = null, array $data = [])
    {
        try {
            $deputado = $this->deputadoRepository->update($id, $data);

            return $deputado;
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

            return true;
        } catch (ModelNotFoundException $exception) {
            return $this->exception($exception, 'Deputado não encontrado.');
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
