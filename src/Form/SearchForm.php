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
    private ?string $searchBy;

    /**
     * @var SearchByList
     */
    private SearchByList $searchByList;

    /**
     * @var string|null
     */
    private ?string $searchPhrase;

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
     * {@inheritdoc}
     */
    public function __toString()
    {
        $this->setAttributes([
            'class' => 'form-inline float-left',
            'onsubmit' => 'return this.querySelector(\'input[name="search"]\').value !== \'\'',
        ]);

//        if (!$this->searchByList->isEmpty()) {
//            $searchBySelect = F::select('Search by', $this->searchByList->getValueOptions())
//                ->setTemplate('');
//        } else {
//            $searchBySelect = '';
//        }

        $submitButton = F::submit('<i class="mdi mdi-magnify"></i>', ['class' => 'btn btn-sm btn-outline-secondary']);

        $searchInput = $this->offsetGet('search');
        $searchInput
            ->setTemplate('<div class="input-group mx-sm-1 mb-2">{{ input }}<div class="input-group-append">' . $submitButton . '</div></div>')
            ->setAttributes([
                'class' => 'form-control form-control-sm',
                'placeholder' => 'Search phrase...',
            ]);

        return $this->getOpeningTag() . $searchInput . $this->getClosingTag();
    }

    /**
     * @return SearchBy|null
     */
    public function getSearchBy(): ?SearchBy
    {
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
     * @param SearchByList $searchByList
     * @return self
     */
    public function withSearchByList(SearchByList $searchByList): self
    {
        $new = clone $this;

        $new->searchByList = $searchByList;

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
        ];
    }
}
