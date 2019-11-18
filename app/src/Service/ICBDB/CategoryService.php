<?php


namespace App\Service\ICBDB;


use App\Interfaces\CategoriesInterface;
use App\Service\AbstractMiscService;
use Exception;

class CategoryService extends AbstractMiscService implements CategoriesInterface
{

    public const EndpointCategories = 'http://api.icndb.com/categories';

    /**
     * @return array
     * @throws Exception
     */
    public function getCategoriesList(): array
    {
        try {

            $body = $this->sendResponse(self::EndpointCategories);

            $result = $this->getResultFromResponse($body);

            $list = $this->getResult($result);

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $list;
    }
}