<?php

namespace BasicCatalogExport\Providers;

use BasicCatalogExport\DataProviders\FirstBaseDataProvider;
use BasicCatalogExport\DataProviders\FirstKeyDataProvider;
use BasicCatalogExport\DataProviders\FirstNestedKeyDataProvider;
use Plenty\Modules\Catalog\Services\UI\Options\UIOptions;
use Plenty\Modules\Catalog\Templates\BaseTemplateProvider;
use BasicCatalogExport\Options\SellerIdOption;
use BasicCatalogExport\Options\TransmitVariationTypeOption;

/**
 * Class BasicCatalogExportTemplateProvider
 * @package BasicCatalogExport\Providers
 */
class BasicCatalogExportTemplateProvider extends BaseTemplateProvider
{
    /**
     * @return array
     */
    public function getMappings(): array
    {
        return [
            [
                'identifier' => 'simpleMapping',
                'label' => 'Base data',
                'isMapping' => false, // simple mapping
                'provider' => FirstBaseDataProvider::class,
            ],
            [
                'identifier' => 'complexMapping',
                'label' => 'Key data',
                'isMapping' => true, // complex mapping
                'provider' => FirstKeyDataProvider::class,
            ],
            [
                'identifier' => 'complexNestedMapping',
                'label' => 'Nested key data',
                'isMapping' => true, // complex mapping
                'provider' => FirstNestedKeyDataProvider::class,
            ]
        ];
    }

    /**
     * @return array
     */
    public function getFilter(): array
    {
        return [];
    }

    /**
     * @return callable[]
     */
    public function getPreMutators(): array
    {
        return [];
    }

    /**
     * @return callable[]
     */
    public function getPostMutators(): array
    {
        return [];
    }

    /**
     * @return callable
     */
    public function getSkuCallback(): callable
    {
        return function ($value, $item) {
            return $value;
        };
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        $options = new UIOptions();

        $options
            ->add(new TransmitVariationTypeOption)
            ->add(new SellerIdOption);

        return $options->toArray();
    }

    /**
     * @return array
     */
    public function getMetaInfo(): array
    {
        return [];
    }
}