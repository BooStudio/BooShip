<?php

namespace BooStudio\BooShip;

class BooShip
{
    protected $username = "";
    protected $token = "";
    const    API_SCHEME   = 'tls://';
    const    API_HOST     = 'shipping.dev.boostudio.com.au';
    const    API_PORT     = 443;                            // ssl port
    const    API_BASE_URL = '/api/shipping/';        // for production use, remove '/test'
    const   HEADER_EOL = "\r\n";

    private $fSock;         // socket handle
    ///shipping/getquotes
    /**
     * BooShip constructor.
     *
     * @param string $username your username
     * @param string $token Token provided by BooStudio
     */
    function __construct()
    {
        $this->username = config('booship.BOOSHIP_USERNAME');
        $this->token = config('booship.BOOSHIP_TOKEN');
    }
    /**
     * Creates a socket connection to the API.
     *
     * @throws Exception if the socket cannot be opened
     */
    private function createSocket()
    {
        $i_timeout = 15;        // seconds
        if (($this->fSock = fsockopen(
                BooShip::API_SCHEME . BooShip::API_HOST,
                BooShip::API_PORT,
                $errno,
                $errstr,
                $i_timeout
            )) === false
        ) {
            throw new Exception('Could not connect to BooShip API: ' . $errstr, $errno);
        }
    }
    /**
     * Builds the HTTP request headers.
     *
     * @param string $s_type        GET/POST/HEAD/DELETE/PUT
     * @param string $s_action      the API action component of the URI
     * @param int    $n_content_len if true, content is included in the request
     * @param bool   $b_incl_accno  if true, include the account number in the header
     *
     * @return array each element is a header line
     */
    private function buildHttpHeaders($s_type, $s_action, $n_content_len = 0, $b_incl_accno = false)
    {
        $a_headers   = array();
        $a_headers[] = $s_type . ' ' . BooShip::API_BASE_URL . $s_action . ' HTTP/1.1';
        $a_headers[] = 'Authorization: ' . 'Basic ' . base64_encode($this->username . ':' . $this->token);
        $a_headers[] = 'Host: ' . BooShip::API_HOST;
        if ($n_content_len) {
            $a_headers[] = 'Content-Type: application/json';
            $a_headers[] = 'Content-Length: ' .
                $n_content_len;     /* Content-Length is a mandatory header field to avoid HTTP 500 errors */
        }
        $a_headers[] = 'Accept: */*';
        $a_headers[] = 'Cache-Control: no-cache';
        $a_headers[] = 'Connection: close';
        return $a_headers;
    }

    /**
     * Sends an HTTP POST request to the API.
     *
     * @param string $s_action the API action component of the URI
     * @param array  $a_data   assoc array containing the data to send
     *
     * @throws Exception on error
     */
    private function sendPostRequest($s_action, $a_data)
    {
        $s_json = json_encode(['data' => $a_data]);

        $this->createSocket();
        $a_headers = $this->buildHttpHeaders('POST', $s_action, strlen($s_json), true);

        if (
            fwrite(
                $this->fSock,
                implode(BooShip::HEADER_EOL, $a_headers) . BooShip::HEADER_EOL . BooShip::HEADER_EOL
            ) === false
        ) {
            throw new Exception('Could not write to Australia Post API');
        }
        if (
            fwrite($this->fSock, $s_json) === false
        ) {
            throw new Exception('Could not write to Australia Post API');
        }
        fflush($this->fSock);
    }

    /**
     * Gets the response from the API.
     *
     * @return array the first element is an array of the header lines, and the second element is an array of the data lines
     */
    private function getResponse()
    {
        $a_hdrs    = $a_data = array();
        $b_in_hdrs = true;
        while (!feof($this->fSock)) {
            $s_line = fgets($this->fSock);
            if ($b_in_hdrs) {
                $s_line = trim($s_line);
                if ($s_line == '') {
                    $b_in_hdrs = false;
                } else {
                    $a_hdrs[] = $s_line;
                }
            } else {
                if (is_string($s_line) && (is_array(json_decode($s_line, true)) ? true : false)) {
                    $a_data[] = trim($s_line);
                }
            }
        }

        return array($a_hdrs, $a_data);
    }

    /**
     * Closes the socket.
     */
    private function closeSocket()
    {
        fclose($this->fSock);
        $this->fSock = false;
    }

    /**
     * Convert the lines of response data into an associative array.
     *
     * @param array $a_data lines of response data
     *
     * @return array    associative array
     */
    private function convertResponse($a_data)
    {
        return json_decode(implode("\n", $a_data), true);
    }

    public function requestdata($type, $data)
    {
        $this->sendPostRequest($type, $data);
        list($a_headers, $a_data) = $this->getResponse();
        $this->closeSocket();

        return $this->convertResponse($a_data);
    }
    public function TestGoodAuth()
    {
        return $this->requestdata('goodauth', []);
    }
    public function GetQuotes($r_data)
    {
        return $this->requestdata('getquotes', $r_data);
    }
}
