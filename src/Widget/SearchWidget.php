<?php

declare(strict_types=1);

/**
 * Search Widget for Mailery Platform
 * @link      https://github.com/maileryio/widget-search
 * @package   Mailery\Widget\Search
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Widget\Search\Widget;

use Mailery\Widget\Search\Assets\SearchAssetBundle;
use Mailery\Widget\Search\Form\SearchForm;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

class SearchWidget extends Widget
{
    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var SearchForm
     */
    private SearchForm $form;

    /**
     * @var AssetManager
     */
    private AssetManager $assetManager;

    /**
     * @param AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
    }

    /**
     * @param array $options
     * @return self
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param SearchForm $form
     * @return self
     */
    public function form(SearchForm $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function run(): string
    {
        $this->assetManager->register(SearchAssetBundle::class);

        $searchBy = $this->form->getSearchBy();

        return (string) Html::tag(
            'ui-widget-search',
            '',
            array_merge(
                $this->options,
                [
                    'search-value' => $this->form->getSearchPhrase(),
                    'search-by-value' => $searchBy ? $searchBy->getValue() : null,
                    'search-by-value-options' => $this->form->getSearchByValueOptions(),
                ]
            )
        );
    }

}
