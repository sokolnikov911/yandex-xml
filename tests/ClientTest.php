<?php declare(strict_types=1);

namespace sokolnikov911\YandexXml;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use sokolnikov911\YandexXml\Exceptions\YandexInvalidArgumentException;

class ClientTest extends TestCase
{
    const KEY = '1234f5be-e67a-890f-1234-afa456dff879';
    const USER = 'user123';

    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client(self::USER,self::KEY);
    }

    public function testAction(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->action();

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'action');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::ACTION_LIMITS_INFO, $reflectedData);
    }

    public function testLr(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->lr(159);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'lr');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(159, $reflectedData);
    }

    public function testL10n(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->l10n(Client::L10N_UKRAINIAN);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'l10n');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::L10N_UKRAINIAN, $reflectedData);
    }

    public function testSortBy(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->sortBy(Client::SORT_BY_RLV);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'sortByType');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::SORT_BY_RLV, $reflectedData);
    }

    public function testSortByWithDirection(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->sortBy(Client::SORT_BY_TM, Client::SORTING_DIRECTION_DESC);

        $reflectedDataSortByType = $this->_getInnerPropertyValueByReflection($initialInstance, 'sortByType');
        $reflectedDataSortByDirection = $this->_getInnerPropertyValueByReflection($initialInstance, 'sortByDirection');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::SORT_BY_TM, $reflectedDataSortByType);
        $this->assertEquals(Client::SORTING_DIRECTION_DESC, $reflectedDataSortByDirection);
    }

    public function testFilter(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->filter(Client::FILTER_MODERATE);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'filter');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::FILTER_MODERATE, $reflectedData);
    }

    public function testMaxPassages(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->maxPassages(3);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'maxPassages');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(3, $reflectedData);
    }

    public function testGroupBy(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->groupBy(Client::GROUP_MODE_FLAT);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'groupByMode');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(Client::GROUP_MODE_FLAT, $reflectedData);
    }

    public function testPage(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->page(4);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'page');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals(4, $reflectedData);
    }

    public function testShowMeCaptcha(): void
    {
        $initialInstance = $this->client;
        $client = $this->client->showMeCaptcha();

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'showMeCaptcha');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals('yes', $reflectedData);
    }

    public function testQuery(): void
    {
        $string = (string) rand();
        $initialInstance = $this->client;
        $client = $this->client->query($string);

        $reflectedData = $this->_getInnerPropertyValueByReflection($initialInstance, 'query');

        $this->assertEquals($initialInstance, $client);
        $this->assertEquals($string, $reflectedData);
    }

    public function testGetEndpointUrl(): void
    {
        $this->assertEquals('https://yandex.ru/search/xml', $this->client->getEndpointUrl());
    }

    public function testGetEndpointUrlWithParamsAction(): void
    {
        $client = $this->client->action();

        $url = 'https://yandex.ru/search/xml?action=' . Client::ACTION_LIMITS_INFO . '&user=' . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithParams(): void
    {
        $client = $this->client->query('query string');

        $url = 'https://yandex.ru/search/xml?query=query+string&user=' . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithSortBy(): void
    {
        $client = $this->client
            ->query('query string')
            ->sortBy(Client::SORT_BY_TM, Client::SORTING_DIRECTION_DESC);

        $url = 'https://yandex.ru/search/xml?query=query+string&sortby=tm.order%3Ddescending&user='
            . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithGroupByDeep(): void
    {
        $client = $this->client
            ->query('query string')
            ->groupBy(Client::GROUP_MODE_DEEP);

        $url = 'https://yandex.ru/search/xml?query=query+string&groupby=deep.attr%3Dd&user='
            . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithGroupByFlat(): void
    {
        $client = $this->client
            ->query('query string')
            ->groupBy(Client::GROUP_MODE_FLAT);

        $url = 'https://yandex.ru/search/xml?query=query+string&groupby=flat&user='
            . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithGroupByFlatGroupsOnPage(): void
    {
        $client = $this->client
            ->query('query string')
            ->groupBy(Client::GROUP_MODE_FLAT, 3);

        $url = 'https://yandex.ru/search/xml?query=query+string&groupby=flat.groups-on-page%3D3&user='
            . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithGroupByFlatDocsInGroup(): void
    {
        $client = $this->client
            ->query('query string')
            ->groupBy(Client::GROUP_MODE_FLAT, null, 2);

        $url = 'https://yandex.ru/search/xml?query=query+string&groupby=flat.docs-in-group%3D2&user='
            . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    public function testGetEndpointUrlWithNewUrl(): void
    {
        $newUrl = 'https://xmldomain.com/search/xml';
        $client = new Client(self::USER,self::KEY, null, $newUrl);

        $client->query('query string');

        $url = $newUrl . '?query=query+string&user=' . self::USER . '&key=' . self::KEY;

        $this->assertEquals($url, $client->getEndpointUrlWithParams());
    }

    /**
     * Return value of a private property using ReflectionClass
     *
     * @param Client $instance
     * @param string $property
     *
     * @return mixed
     * @throws ReflectionException
     */
    private function _getInnerPropertyValueByReflection(Client $instance, string $property)
    {
        $reflector = new ReflectionClass($instance);
        $reflector_property = $reflector->getProperty($property);
        $reflector_property->setAccessible(true);

        return $reflector_property->getValue($instance);
    }
}