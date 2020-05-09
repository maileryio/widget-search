<?php

declare(strict_types=1);

/**
 * Search Widget for Mailery Platform
 * @link      https://github.com/maileryio/widget-search
 * @package   Mailery\Widget\Search
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Widget\Search\Form;

use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Widget\Search\Model\SearchBy;
use Mailery\Widget\Search\Model\SearchByList;
use Psr\Http\Message\ServerRequestInterface as Request;

class SearchForm extends Form
{
    /**
     * @var string|null
     */
    private ?string $searchBy = null;

    /**
     * @var SearchByList|null
     */
    private ?SearchByList $searchByList = null;

    /**
     * @var string|null
     */
    private ?string $searchPhrase = null;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $queryParams = $request->getQueryParams();

        $this->searchBy = $queryParams['searchBy'] ?? null;
        $this->searchPhrase = $queryParams['search'] ?? null;
        parent::__construct($this->inputs());
    }

    /**
     * @return SearchBy|null
     */
    public function getSearchBy(): ?SearchBy
    {
        if ($this->searchByList === null) {
            return null;
        }

        return $this->searchByList->findByValue($this->searchBy);
    }

    /**
     * @return string|null
     */
    public function getSearchPhrase(): ?string
    {
        return $this->searchPhrase;
    }

    /**
     * @return array
     */
    public function getSearchByValueOptions(): array
    {
        if ($this->searchByList === null) {
            return [];
        }

        return $this->searchByList->getValueOptions();
    }

    /**
     * @param SearchByList $searchByList
     * @return self
     */
    public function withSearchByList(SearchByList $searchByList): self
    {
        $new = clone $this;

        $new->searchByList = $searchByList;
        $this['searchBy']->setOptions($this->getSearchByValueOptions());

        return $new;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        return [
            'search' => F::text('Search phrase...')
                ->setValue($this->searchPhrase),
            'searchBy' => F::select('Search by', $this->getSearchByValueOptions()),
        ];
    }
}
