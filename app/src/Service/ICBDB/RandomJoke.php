<?php


namespace App\Service\ICBDB;

use App\Interfaces\JokeInterface;
use App\Service\AbstractMiscService;
use Exception;

class RandomJoke extends AbstractMiscService implements JokeInterface
{

    public const EndpointRandomJokeWithCategories = 'http://api.icndb.com/jokes/random?limitTo=[%s]';
    public const EndpointRandomJokeWithoutCategories = 'http://api.icndb.com/jokes/random';

    /**
     * @param array $categories
     * @return array
     * @throws Exception
     */
    public function getJoke(array $categories=[]): array
    {
        try {

            // Get string from categories array for url string
            $path = count($categories)?
                sprintf(self::EndpointRandomJokeWithCategories,implode(',', $categories)):
                self::EndpointRandomJokeWithoutCategories;

            $body = $this->sendResponse($path);

            $result = $this->getResultFromResponse($body);

            $joke_array = $this->getResult($result);

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
        return $joke_array;
    }

}