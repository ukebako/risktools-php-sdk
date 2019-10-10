<?php namespace RiskTools;

/**
 * RiskTools API client
 */
class Client
{
    public $url;
    public $key;

    public $response;
    public $error;
    public $execTime;
    public $rawResponse;

    public function __construct($key, $url = null, $throwServerErrors = true)
    {
        $this->key = $key;
        $this->throwServerErrors = $throwServerErrors;

        if (!is_null($url)) {
            $this->url = rtrim($url, '/').'/';
        }
    }

    /**
     * Executes server api method
     *
     * @param strign $method                Server api method like /order/init_order
     * @param array $data                   Method options
     *
     */
    public function exec($method, array $data = [], $decode = true)
    {
        $this->response = null;
        $this->error = null;
        $this->execTime = null;
        $this->rawResponse = null;

        $ch = curl_init($this->url.ltrim($method, '/'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'AuthKey: '.$this->key,
            'Content-type: application/json'
        ]);

        $this->rawResponse = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response_code != 200) {
            throw new ClientException('Server returned ['.$response_code.'] HTTP code', $response_code);
        }

        $data = json_decode($this->rawResponse, true);
        if (is_null($data)) {
            throw new ClientException('Api response was not decoded');
        }

        if (!array_key_exists('status', $data)) {
            throw new ClientException('Unexpected response format');
        }

        $this->execTime = $data['exec_time_ms'] ?? null;

        if ($data['status'] == 'error') {
            $this->error = new ServerException($data['error']['message'], $data['error']['code'], $data['error']['origin']);
            if ($this->throwServerErrors) {
                throw $this->error;
            }
        }
        elseif ($data['status'] == 'ok') {
            $this->response = $data['response'];
        }

        return $this->response;

    }
}
