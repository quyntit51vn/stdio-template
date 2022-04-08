<?php

namespace Stdio\StdioTemplate\Repository\Eloquent;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use Stdio\StdioTemplate\Repository\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{

    protected $model;

    /**
     * @param Application $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract function model();

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model;
    }

    /**
     * Retrieve all data of repository
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $limit = is_null($limit) ? 10 : $limit;
        if ($page != null) {
            return $this->model->paginate($limit, $columns, $pageName, $page);
        }
        return $this->model->paginate($limit, $columns);
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Save a new entity in repository
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function create(array $input)
    {
        return $this->model->create($input);
    }

    /**
     * Update a entity in repository by id
     *
     * @param array $input
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $input, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
