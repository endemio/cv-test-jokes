<?php


namespace App\Service;


use App\Exceptions\ErrorDuringResponseToGetCategories;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\ClientException;

abstract class AbstractMiscService
{

    private $url;

    /**
     * @param string $url
     * @return string
     * @throws Exception
     * @throws ErrorDuringResponseToGetCategories
     */
    public function sendResponse(string $url): string
    {
        $client = new Client();

        $this->url = $url;

        try {
            $response = $client->get($url);
        } catch (ClientException $exception) {
            throw new Exception($exception->getMessage());
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
        } else {
            throw new ErrorDuringResponseToGetCategories('We get ' . $response->getStatusCode() . ' code, not 200');
        }

        return $body;
    }

    /**
     * @param string $body
     * @return array
     * @throws Exception
     */
    public function getResultFromResponse(string $body): array
    {

        $result = json_decode($body, true);

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Its not json string');
        }

        return $result;
    }

    /**
     * @param array $input
     * @return array
     * @throws ErrorDuringResponseToGetCategories
     */
    public function getResult(array $input): array
    {
        if (isset($input['type'])) {
            if ($input['type'] === "success") {
                if (isset($input['value'])) {
                    $list = $input['value'];
                } else {
                    throw new ErrorDuringResponseToGetCategories(sprintf('Answer from %s hasn\'t "value"', $this->url));
                }
            } else {
                throw new ErrorDuringResponseToGetCategories(sprintf('Answer from %s wasn\'t "success"', $this->url));
            }
        } else {
            throw new ErrorDuringResponseToGetCategories(sprintf('Answer from %s hasn\'t "type"', $this->url));
        }

        return $list;
    }


}