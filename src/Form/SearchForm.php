<?php

namespace Mailery\Widget\Search;

use FormManager\Factory as F;
use FormManager\Form;
use Psr\Http\Message\ServerRequestInterface as Request;

class SearchForm extends Form
{
    /**
     * @var array
     */
    private array $contexts = [];

    /**
     * @var string|null
     */
    private ?string $searchBy;

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
     * @param ContextInterface $context
     * @return self
     */
    public function addSearchByContext(SearchByContextInterface $context): self
    {
        $new = clone $this;

        $new->contexts[] = $context;
        return $new;
    }

    /**
     * @return ContextInterface|null
     */
    public function getSearchByContext(): ?ContextInterface
    {
        foreach ($this->contexts as $context) {
            if ($context->match($this->searchBy)) {
                return $context;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $this->setAttributes([
            'class' => 'form-inline float-left',
            'onsubmit' => 'return this.querySelector(\'input[name="search"]\').value !== \'\'',
        ]);

        $searchByOptions = $this->getSearchByOptions();
        if (!empty($searchByOptions)) {
            $searchBySelect = F::select('Search by', $this->getSearchByOptions())
                ->setTemplate('');
        } else {
            $searchBySelect = '';
        }

        $submitButton = F::submit('<i class="mdi mdi-magnify"></i>', ['class' => 'btn btn-sm btn-outline-secondary']);

        $searchInput = $this['search'];
        $searchInput
            ->setTemplate('<div class="input-group mx-sm-1 mb-2">{{ input }}<div class="input-group-append">' . $submitButton . '</div></div>')
            ->setAttributes([
                'class' => 'form-control form-control-sm',
                'placeholder' => 'Search phrase...',
            ]);

        return parent::__toString();
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

    /**
     * @return array
     */
    private function getSearchByOptions(): array
    {
        return array_map(
            function (ContextInterface $context) {
                return $context->getOptionValue();
            },
            $this->contexts
        );
    }

}
