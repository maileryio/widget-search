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

use Mailery\Widget\Search\Model\SearchBy;
use Mailery\Widget\Search\Model\SearchByList;
use Yiisoft\Form\FormModel;

class SearchForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $searchBy = null;

    /**
     * @var string|null
     */
    private ?string $searchPhrase = null;

    /**
     * @var SearchByList|null
     */
    private ?SearchByList $searchByList = null;

    /**
     * @param string|null $searchBy
     * @return self
     */
    public function withSearchBy(?string $searchBy): self
    {
        $new = clone $this;
        $new->searchBy = $searchBy;

        return $new;
    }

    /**
     * @param string|null $searchPhrase
     * @return self
     */
    public function withSearchPhrase(?string $searchPhrase): self
    {
        $new = clone $this;
        $new->searchPhrase = $searchPhrase;

        return $new;
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
     * @return SearchBy|null
     */
    public function getSearchBy(): ?SearchBy
    {
        if ($this->searchByList === null || $this->searchPhrase === null) {
            return null;
        }

        if (($searchBy = $this->searchByList->findByValue($this->searchBy)) !== null) {
            return $searchBy->withSearchPhrase($this->searchPhrase);
        }

        return null;
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
    public function getAttributeLabels(): array
    {
        return [
            'searchBy' => 'Search by',
            'searchPhrase' => 'Search phrase...',
        ];
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
}
