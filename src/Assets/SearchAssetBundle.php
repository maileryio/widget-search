<?php

declare(strict_types=1);

/**
 * Search Widget for Mailery Platform
 * @link      https://github.com/maileryio/widget-search
 * @package   Mailery\Widget\Search
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Widget\Search\Assets;

use Mailery\Web\Assets\VueAssetBundle;
use Yiisoft\Assets\AssetBundle;

class SearchAssetBundle extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public ?string $basePath = '@public/assets/@maileryio/widget-search-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $baseUrl = '@assetsUrl/@maileryio/widget-search-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $sourcePath = '@npm/@maileryio/widget-search-assets/dist';

    /**
     * {@inheritdoc}
     */
    public array $js = [
        'main.umd.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public array $css = [
        'main.min.css',
    ];

    /**
     * {@inheritdoc}
     */
    public array $depends = [
        VueAssetBundle::class,
    ];
}
