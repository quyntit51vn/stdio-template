<?php

namespace Stdio\StdioTemplate\DTO;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class BaseDTO extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private $type;
    public function __construct($resource, $type = null)
    {
        parent::__construct($resource);

        $this->type = $type;
    }

    /**
     * get data
     */
    protected function get($model, $data)
    {
        $data = json_decode(json_encode($data), true);
        if (!is_array_not_object($data))
            return $this->filterRequest($data, $model);

        $result = [];
        foreach ($data as $value) {
            $result[] = $this->filterRequest($value, $model);
        }
        return $result;
    }

    /**
     * @param $request
     * @param $filter
     * @param null $origin
     * @return array
     * 
     * filter:
     * - value
     * - key => ['rename' => '', 'position' => '', 'arrayFormat' => []]
     *   + rename: đổi tên trường (option)
     *   + position: position data (option)
     *   + arrayFormat: array child (option)
     *   + resource: handle resource (option)
     *     . model: class resource (option)
     *     . type: type resource (option)
     * Example
     * 
     * $filter = ['id',
     * 'phone' => ['rename' => 'old_phone', 'position' => 'user;phone'],
     * 'phone' => ['rename' => 'new_phone', 'position' => 'user;id'],
     * 'name' => ['rename' => 'name_name', 'arrayFormat' => ['???' => ['position' => 'user;name'], '!!!' => ['position' => 'id']]],
     * 'user' => ['arrayFormat' => ['id', 'phone']],
     * 'test' => ['rename' => 'tester', 'position' => 'user', 'arrayFormat' => ['id', 'phone']],
     * 'resource_test' => ['resource' => ['model' => 'Company', 'type' => 'DETAIL']]];
     * $request = ['id' => 1, 'name' => 'ABC', 'phone' => '073', 'user' => ['id' => 2, 'name' => 'xyz', 'phone' => '0935']];
     */
    public function filterRequest($request, $filter, $origin = null)
    {
        try {
            //  Set origin request to query position
            if (!isset($origin)) {
                $origin = $request;
            }
            $data = [];
            foreach ($filter as $key => $value) {
                if (is_array($value)) {
                    //  query absolute data
                    $query = isset($value['position'])
                        ? $this->getDataByPosition($origin, $value['position'])
                        : $request[$key];

                    if (isset($value['arrayFormat'])) {
                        $query = $this->filterRequest($query, $value['arrayFormat'], $origin);
                    }

                    //  filter data
                    if (isset($value['resource'])) {
                        $classResource = $this->getClassResouce($value['resource']['model']);
                        if (!class_exists($classResource)) {
                            throw new \Exception('Resource not found', Response::HTTP_NOT_FOUND);
                        }
                        $type = $value['resource']['type'];
                        $query = $this->executeResource($classResource, $query, $type);
                    }

                    // rename key
                    $key = isset($value['rename']) ? $value['rename'] : $key;
                    $data[$key] = $query;
                } else {
                    $query = isset($request[$value]) ? $request[$value] : null;
                    $data[$value] = $query;
                }
            }
        } catch (\Throwable $th) {
            Log::stack(['state'])->info("Error:" . $th->getMessage() . " \nLine:" . $th->getCode());
            throw $th;
        }
        return $data;
    }

    private function getClassResouce($model)
    {
        $class = __NAMESPACE__ . '\\' . $model;
        if (substr($model, -8) != 'Resource') {
            $class .= 'Resource';
        }
        return $class;
    }
    /**
     * @param $resource name class resource
     * @param $data data origin
     * @param $type type resource
     * 
     * @return new class resource 
     */
    private function executeResource($resource, $data, $type)
    {
        return new $resource($data, defined("$resource::$type") ? constant("$resource::$type") : NULL);
    }

    /**
     * @param $data 
     * @param $directory 
     * 
     * @return new class resource 
     */
    private function getDataByPosition($data, $directory)
    {
        try {
            $arrayKey = explode(";", $directory);
            foreach ($arrayKey as $next) {
                $data = isset($data[$next]) ? $data[$next] : null;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $data;
    }
}
