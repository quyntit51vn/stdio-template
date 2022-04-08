<?php

namespace Stdio\StdioTemplate\Repository\Contracts;

interface RepositoryInterface
{
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Save a new entity in repository
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function create(array $inputs);

    /**
     * Update a entity in repository by id
     *
     * @param array $input
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $inputs, $id);

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id);
}
