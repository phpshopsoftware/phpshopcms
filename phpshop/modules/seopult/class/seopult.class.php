<?php

class Seopult {

    /**
     * ��������� ��������� �� �������
     * @var array
     */
    protected $_errors = array();

    /**
     * ������ API
     * �������� �������� �� ��, ��� ��� ��������� ����� ������������ ��������������� ������
     * @var array
     */
    public $_credentials = array(
        'HASH' => '7a52518f2d1b22983a51a2fbf2a8ec75',
        'URL' => ''
    );

    /**
     * ���������, ���� ����� ������������ ������
     * �������� ������� - https://api-3t.paypal.com/nvp
     * ��������� - https://api-3t.sandbox.paypal.com/nvp
     * @var string
     */
    public $_endPoint = 'http://seopult.pro';

    /**
     * ������ API
     * @var string
     */
    protected $_version = '106.0';

    /**
     * �������������� ������
     *
     * @param string $method ������ � ���������� ������ ��������
     * @param array $params �������������� ���������
     * @return array / boolean Response array / boolean false on failure
     */
    public function request($method, $params = array()) {
        $this->_errors = array();
        if (empty($method)) { // ���������, ������ �� ������ �������
            $this->_errors = array('�� ������ ����� �������� �������');
            return false;
        }

        // ��������� ������ �������
        $requestParams = array(
            'METHOD' => $method,
            'VERSION' => $this->_version
                ) + $this->_credentials;

        // �������������� ������ ��� NVP
        $request = http_build_query($requestParams + $params);

        // ����������� cURL
        $curlOptions = array(
            CURLOPT_URL => $this->_endPoint,
            CURLOPT_VERBOSE => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', // ���� �����������
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);

        // ���������� ��� ������, $response ����� ��������� ����� �� API
        $response = curl_exec($ch);

        // ���������, ���� �� ������ � ������������� cURL
        if (curl_errno($ch)) {
            $this->_errors = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            $responseArray = array();
            parse_str($response, $responseArray); // ��������� ������, ���������� �� NVP � ������
            return $responseArray;
        }
    }
}

?>
