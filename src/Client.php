<?php

namespace sokolnikov911\YandexXml;

use sokolnikov911\YandexXml\Exceptions\YandexException;
use sokolnikov911\YandexXml\Exceptions\YandexInvalidArgumentException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private $user;
    private $key;
    private $selectedLanguage;

    private $lr;
    private $l10n;
    private $sortByType;
    private $sortByDirection;
    private $filter;
    private $maxPassages;
    private $groupByMode;
    private $groupByGroupsOnPage;
    private $groupByDocsInGroup;
    private $page;
    private $showMeCaptcha;
    private $action;
    private $query;

    private $url = 'https://yandex.{lang}/search/xml';

    const AVAILABLE_REGIONS = [
        '225', '187', '149', '159', '20', '1092', '37', '30', '197', '47', '4', '65', '77', '66', '191', '10', '24',
        '48', '75', '49', '33', '50', '192', '25', '38', '39', '21', '11', '193', '51', '1106', '2', '54', '42', '5',
        '12', '63', '239', '41', '36', '43', '973', '22', '13', '64', '14', '7', '67', '35', '15', '62', '195', '53',
        '172', '8', '76', '9', '45', '28', '56', '1', '1104', '213', '16', '23'
    ];

    const DOMAIN_RU = 'ru';
    const DOMAIN_COM_TR = 'com.tr';
    const DOMAIN_COM = 'com';

    const ACTION_LIMITS_INFO = 'limits-info';

    const L10N_RUSSIAN = 'ru';
    const L10N_UKRAINIAN = 'uk';
    const L10N_BELARUSIAN = 'be';
    const L10N_KAZAKH = 'kk';
    const L10N_TURKISH = 'tr';
    const L10N_ENGLISH = 'en';

    const SORT_BY_RLV = 'rlv';
    const SORT_BY_TM = 'tm';

    const SORTING_DIRECTION_DESC = 'descending';
    const SORTING_DIRECTION_ASC = 'ascending';

    const FILTER_STRICT = 'strict';
    const FILTER_MODERATE = 'moderate';
    const FILTER_NONE = 'none';

    const GROUP_MODE_FLAT = 'flat';
    const GROUP_MODE_DEEP = 'deep';

    /**
     * @param string $user API user
     * @param string $key API key
     * @param string|null $domainLanguage (optional) Domain language
     * @param string|null $endpointUrl
     * @see https://yandex.ru/dev/xml/
     */
    public function __construct(string $user, string $key, ?string $domainLanguage = null,
                                ?string $endpointUrl = null)
    {
        $this->user = $user;
        $this->key = $key;
        $this->selectedLanguage = $domainLanguage;

        if (!$domainLanguage) {
            $this->selectedLanguage = self::DOMAIN_RU;
        }

        if ($endpointUrl) {
            $this->url = $endpointUrl;
        }
    }

    /**
     * Set a search region id. Region list you can find here: https://yandex.ru/dev/xml/doc/dg/reference/regions.html/
     *
     * @param integer $lr region id
     * @return Client $client
     * @throws YandexInvalidArgumentException
     * @see https://yandex.ru/dev/xml/doc/dg/reference/regions.html/
     */
    public function lr(int $lr): Client
    {
        if (!in_array($lr, self::AVAILABLE_REGIONS)) {
            throw new YandexInvalidArgumentException();
        }

        $this->lr = $lr;

        return $this;
    }

    /**
     * Set a response language
     *
     * @param string $l10n region id
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function l10n(string $l10n): Client
    {
        if (!in_array($l10n, [self::L10N_TURKISH, self::L10N_ENGLISH, self::L10N_BELARUSIAN, self::L10N_KAZAKH,
            self::L10N_RUSSIAN, self::L10N_UKRAINIAN])) {
            throw new YandexInvalidArgumentException();
        }

        if (($l10n == self::L10N_TURKISH) && ($this->selectedLanguage != self::DOMAIN_COM_TR)) {
            throw new YandexInvalidArgumentException();
        }

        if (($l10n == self::L10N_ENGLISH) && ($this->selectedLanguage != self::DOMAIN_COM)) {
            throw new YandexInvalidArgumentException();
        }

        $this->l10n = $l10n;

        return $this;
    }

    /**
     * Set a search region id. Region list you can find here: https://yandex.ru/dev/xml/doc/dg/reference/regions.html/
     *
     * @param string $type sorting type
     * @param string|null $direction (optional) sorting direction
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function sortBy(string $type, ?string $direction = null): Client
    {
        if (!in_array($type, [self::SORT_BY_TM, self::SORT_BY_RLV])) {
            throw new YandexInvalidArgumentException();
        }

        if (($type == self::SORT_BY_TM) && $direction) {
            if (!in_array($direction, [self::SORTING_DIRECTION_ASC, self::SORTING_DIRECTION_DESC])) {
                throw new YandexInvalidArgumentException();
            }

            $this->sortByDirection = $direction;
        }

        $this->sortByType = $type;

        return $this;
    }

    /**
     * Set a filtering rules
     *
     * @param string $type filtering type
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function filter(string $type): Client
    {
        if (!in_array($type, [self::FILTER_MODERATE, self::FILTER_NONE, self::FILTER_STRICT])) {
            throw new YandexInvalidArgumentException();
        }

        $this->filter = $type;

        return $this;
    }

    /**
     * Set a maximum passages number
     *
     * @param integer $number maximum passages number
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function maxPassages(int $number): Client
    {
        if (($number < 1) || ($number > 5)) {
            throw new YandexInvalidArgumentException();
        }

        $this->maxPassages = $number;

        return $this;
    }

    /**
     * Set a grouping rules
     *
     * @param string $mode grouping method
     * @param integer|null $groupsOnPage (optional) groups on page number
     * @param integer|null $docsInGroup (optional) documents in group number
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function groupBy(string $mode,
                            ?int $groupsOnPage = null,
                            ?int $docsInGroup = null): Client
    {
        if (!in_array($mode, [self::GROUP_MODE_DEEP, self::GROUP_MODE_FLAT])) {
            throw new YandexInvalidArgumentException();
        }

        if ($groupsOnPage && (($groupsOnPage < 1) || ($groupsOnPage > 100))) {
            throw new YandexInvalidArgumentException();
        }

        if ($docsInGroup && (($docsInGroup < 1) || ($docsInGroup > 3))) {
            throw new YandexInvalidArgumentException();
        }

        $this->groupByMode = $mode;

        if ($groupsOnPage) {
            $this->groupByGroupsOnPage = $groupsOnPage;
        }

        if ($docsInGroup) {
            $this->groupByDocsInGroup = $docsInGroup;
        }

        return $this;
    }

    /**
     * Set a page number
     *
     * @param integer $number page number. Starts from "0"
     * @throws YandexInvalidArgumentException
     * @return Client $client
     */
    public function page(int $number): Client
    {
        if ($number < 0) {
            throw new YandexInvalidArgumentException();
        }

        $this->page = $number;

        return $this;
    }

    /**
     * Enable Captcha on result page
     *
     * @return Client $client
     */
    public function showMeCaptcha(): Client
    {
        $this->showMeCaptcha = 'yes';

        return $this;
    }

    /**
     * Set query string
     *
     * @param string $query query string
     * @return Client $client
     */
    public function query(string $query): Client
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set action value (at this time uses only for getting information about day or hour limits)
     *
     * @return Client $client
     */
    public function action(): Client
    {
        $this->action = self::ACTION_LIMITS_INFO;

        return $this;
    }

    /**
     * Retrieving search results
     *
     * @throws YandexException|ClientException|GuzzleException
     * @return Client $client
     */
    public function get(): string
    {
        $data = $this->getData();

        return $data ? $data : '';
    }

    /**
     * Sends a request
     *
     * @return string Response body
     * @throws YandexException|ClientException|GuzzleException
     */
    protected function getData(): string
    {
        $url = $this->getEndpointUrlWithParams();

        $client = new HttpClient();

        try {
            $response = $client->get($url);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseData = $response->getBody()->getContents();

            $xml = simplexml_load_string($responseData);
            $responseData = json_encode($xml, JSON_UNESCAPED_UNICODE);

            $dataArray = json_decode($responseData, true);

            if ($dataArray['response'] && $dataArray['response']['error']) {
                throw new YandexException($dataArray['error']['text'], $dataArray['response']['error']);
            } else throw $e;
        }

        return $response->getBody();
    }

    private function generateSortByAttribute(): string
    {
        $string = $this->sortByType;

        if ($this->sortByDirection) {
            $string .= '.order=' . $this->sortByDirection;
        }

        return $string;
    }

    private function generateGroupByAttribute(): string
    {
        $string = $this->groupByMode;

        if ($this->groupByMode == self::GROUP_MODE_DEEP) {
            $string .= '.attr=d';
        }

        if ($this->groupByGroupsOnPage) {
            $string .= '.groups-on-page=' . $this->groupByGroupsOnPage;
        }

        if ($this->groupByDocsInGroup) {
            $string .= '.docs-in-group=' . $this->groupByDocsInGroup;
        }

        return $string;
    }

    /**
     * Generates end-point URL with parameters
     *
     * @return string Full end-point URL
     * @throws YandexException
     */
    public function getEndpointUrlWithParams(): string
    {
        $url = $this->getEndpointUrl();
        $params = [];

        if ($this->action) {
            $params['action'] = $this->action;
        } else {
            if (!$this->query) {
                throw new YandexException();
            }

            $params['query'] = $this->query;

            if ($this->lr) $params['lr'] = $this->lr;
            if ($this->l10n) $params['l10n'] = $this->l10n;
            if ($this->page) $params['page'] = $this->page;
            if ($this->showMeCaptcha) $params['showmecaptcha'] = $this->showMeCaptcha;
            if ($this->maxPassages) $params['maxpassages'] = $this->maxPassages;
            if ($this->filter) $params['filter'] = $this->filter;
            if ($this->sortByType) $params['sortby'] = $this->generateSortByAttribute();
            if ($this->groupByMode) $params['groupby'] = $this->generateGroupByAttribute();
        }

        if (!$params) {
            throw new YandexException();
        }

        $params['user'] = $this->user;
        $params['key'] = $this->key;

        return $url . '?' . http_build_query($params);
    }

    /**
     * Generates end-point URL
     *
     * @return string Full end-point URL
     */
    public function getEndpointUrl(): string
    {
        return str_replace('{lang}', $this->selectedLanguage, $this->url);
    }
}