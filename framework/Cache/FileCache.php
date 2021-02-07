<?php


namespace Framework\Cache;


use Framework\Config;
use Framework\Exceptions\UnknownCacheDriverException;

class FileCache implements ICache
{
    protected $db;
    protected $data;

    /**
     * FileCache constructor.
     * @throws UnknownCacheDriverException
     */
    public function __construct()
    {
        $this->db = Config::get('cache.path');
        if ($this->db === null) {
            throw new UnknownCacheDriverException();
        }
    }

    private function getData()
    {
        return file_get_contents($this->db);
    }


    /**
     * @param  string  $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        $data = json_decode($this->getData(), true);

        if (isset($data->$key)) {
             return json_decode($data->$key, true);
        }

        return null;
    }

    public function set(string $key, $value): void
    {
        $data = json_decode($this->getData(), true);

        $data[$key] = json_encode($value);

        file_put_contents($this->db, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function delete(string $key)
    {
        $data = json_decode($this->getData(), true);
        unset($data[$key]);
        file_put_contents($this->db, json_encode($data));
    }

    public function flush()
    {
        file_put_contents($this->db, json_encode([], JSON_PRETTY_PRINT));
    }
}
