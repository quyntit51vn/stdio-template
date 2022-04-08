<?php

namespace App\StdioHelpers;

use Illuminate\Support\Collection;

class CollectionStdio extends Collection
{
    const SORT_DESC = 'DESC';
    public function paginate($per_page, $current_page = 1)
    {
        $current_page = (int)$current_page;
        if ($current_page <= 0) $current_page = 1;
        $data = [];
        $total_record = count($this->items);
        $from_record = ($current_page - 1) * $per_page + 1;
        $total_page = $total_record / $per_page + ($total_record % $per_page ? 1 : 0);
        $data = [
            "current_page" => $current_page,
            "total" => $total_record,
            "per_page" => $per_page,
            "to" => $from_record + $per_page - 1,
            "from" => $from_record,
            "last_page" => (int)$total_page
        ];

        $data['data'] = $this->splice($from_record -  1, $per_page)->values();
        return new static($data);
    }

    public function unsets(array $keys)
    {
        foreach ($keys as $key) {
            $this->unset($key);
        }
    }

    public function unset(string $key)
    {
        $this->forget($key);
    }
    /**
     * 
     * @param callback: is able function or array key sort
     *          array key format example: ['id','name'] => priority asc id -> name
     *          array key format example: ['id','name','order;user;name'] => priority asc id -> name -> order[user][name]
     * @param desc: is string (apply for all key) or array string inclu ['ASC', 'DESC']. Default DESC
     * 
     * @return CollectionStdio has been sorted
     */
    public function orderBy($callback, $desc = 'DESC')
    {
        $descs = explode(" ", $desc);
        $descendings = [];
        foreach ($descs as $value) {
            $descendings[] = mb_strtoupper($value) == self::SORT_DESC ? true : false;
        }
        if (!count($descendings)) $descendings[] = true;
        if (!is_function($callback)) {
            $array_key = $callback;
            return $this->sort(function ($item1, $item2) use ($array_key, $descendings) {
                $compare = 0;
                $length_desc = count($descendings);
                foreach ($array_key as $key_desc => $value) {
                    $array_key_child = explode(";", $value);
                    $data_tmp1 = $item1;
                    $data_tmp2 = $item2;
                    foreach ($array_key_child as $value_child) {
                        $data_tmp1 = $data_tmp1[$value_child];
                        $data_tmp2 = $data_tmp2[$value_child];
                    }

                    if ((is_numeric($data_tmp1) && is_numeric($data_tmp2))
                        || $data_tmp1 === null ||  $data_tmp2 === null
                    ) {
                        $compare = (float)$data_tmp1 > (float)$data_tmp2 ? 1
                            : ((float)$data_tmp1 < (float)$data_tmp2 ? -1 : 0);
                    } else {
                        $compare = strcmp(convert_unicode_utf8($data_tmp1), convert_unicode_utf8($data_tmp2));
                    }
                    $descending = $key_desc < $length_desc
                        ? $descendings[$key_desc] : $descendings[$length_desc - 1];

                    if ($compare) {
                        return $descending ? -$compare : $compare;
                    }
                }

                return is_array($descending) ? ($descending[0] ? -$compare : $compare) :  -$compare;
            });
        }

        return $this->sortBy($callback, SORT_NATURAL | SORT_FLAG_CASE, $descendings[0]);
    }
}
