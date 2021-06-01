<?php

namespace BasicCatalogExport\Providers;

use BasicCatalogExport\DynamicConfig\ExampleDynamicConfig;
use Plenty\Modules\Catalog\Containers\CatalogTemplateFieldContainer;
use Plenty\Modules\Catalog\Containers\Filters\CatalogFilterBuilderContainer;
use Plenty\Modules\Catalog\Containers\TemplateGroupContainer;
use Plenty\Modules\Catalog\Contracts\CatalogDynamicConfigContract;
use Plenty\Modules\Catalog\Legacy\ElasticExport\Manufacturer\Templates\Standard\Filters\LegacyManufacturerFilterBuilder;
use Plenty\Modules\Catalog\Models\CombinedTemplateField;
use Plenty\Modules\Catalog\Models\ComplexTemplateField;
use Plenty\Modules\Catalog\Models\SimpleTemplateField;
use Plenty\Modules\Catalog\Models\TemplateGroup;
use Plenty\Modules\Catalog\Templates\Providers\AbstractGroupedTemplateProvider;
use Plenty\Modules\Item\Catalog\ExportTypes\Variation\Filters\Builders\VariationHasSku;
use Plenty\Modules\Item\Catalog\ExportTypes\Variation\Filters\Builders\VariationIsActive;

/**
 * Class ExampleTemplateProvider
 * @package BasicCatalogExport\Providers
 */
class ExampleTemplateProvider extends AbstractGroupedTemplateProvider
{
    public function getTemplateGroupContainer(): TemplateGroupContainer
    {
        /** @var TemplateGroupContainer $templateGroupContainer */
        $templateGroupContainer = pluginApp(TemplateGroupContainer::class);

        // Simple fields

        /** @var TemplateGroup $simpleGroup */
        $simpleGroup = pluginApp(TemplateGroup::class,
            [
                "identifier" => "groupOne",
                "label" => "Simple fields" // In a productive plugin this should be translated
            ]);

        /** @var SimpleTemplateField $name */
        $name = pluginApp(SimpleTemplateField::class, [
            'variationName',
            'name',
            'Variation name', // In a productive plugin this should be translated
            true
        ]);

        /** @var SimpleTemplateField $price */
        $price = pluginApp(SimpleTemplateField::class, [
            'price',
            'price',
            'Sales price', // In a productive plugin this should be translated
            true
        ]);

        /** @var SimpleTemplateField $sku */
        $sku = pluginApp(SimpleTemplateField::class, [
            'sku',
            'sku',
            'SKU', // In a productive plugin this should be translated
            true
        ]);

        /** @var SimpleTemplateField $stock */
        $stock = pluginApp(SimpleTemplateField::class, [
            'stock',
            'stock',
            'Stock', // In a productive plugin this should be translated
            true,
            false,
            false,
            [],
            [
                [
                    'fieldId' => 'stock-0',
                    'id' => 0,
                    'isCombined' => false,
                    'key' => null,
                    'type' => "stock",
                    'value' => null
                ]
            ]
        ]);

        $simpleGroup->addGroupField($name);
        $simpleGroup->addGroupField($price);
        $simpleGroup->addGroupField($sku);
        $simpleGroup->addGroupField($stock);

        $templateGroupContainer->addGroup($simpleGroup);

        // Complex field

        /** @var TemplateGroup $complexGroup */
        $complexGroup = pluginApp(TemplateGroup::class,
            [
                "identifier" => "groupTwo",
                "label" => "Complex fields" // In a productive plugin this should be translated
            ]);

        /** @var ComplexTemplateField $name */
        $category = pluginApp(ComplexTemplateField::class, [
            'category',
            'category',
            'Category', // In a productive plugin this should be translated
            pluginApp(ExampleCategoryMappingValueProvider::class),
            true
        ]);

        $complexGroup->addGroupField($category);
        $templateGroupContainer->addGroup($complexGroup);

        // Combined field

        /** @var TemplateGroup $combinedGroup */
        $combinedGroup = pluginApp(TemplateGroup::class,
            [
                "identifier" => "groupThree",
                "label" => "Combined fields" // In a productive plugin this should be translated
            ]);

        /** @var CatalogTemplateFieldContainer $simpleContainer */
        $simpleContainer = pluginApp(CatalogTemplateFieldContainer::class);

        /** @var SimpleTemplateField $name */
        $barcode = pluginApp(SimpleTemplateField::class, [
            'barcode',
            'barcode',
            'Barcode',
            true
        ]);

        $simpleContainer->addField($barcode);

        /** @var CombinedTemplateField $name */
        $barcodeType = pluginApp(CombinedTemplateField::class, [
            'barcodeType',
            'barcodeType',
            'Barcode type', // In a productive plugin this should be translated
            pluginApp(ExampleBarcodeTypeMappingValueProvider::class),
            $simpleContainer
        ]);

        $combinedGroup->addGroupField($barcodeType);
        $templateGroupContainer->addGroup($combinedGroup);

        return $templateGroupContainer;
    }

    public function getFilterContainer(): CatalogFilterBuilderContainer
    {
        /** @var CatalogFilterBuilderContainer $container */
        $container = pluginApp(CatalogFilterBuilderContainer::class);

        /** @var VariationIsActive $variationIsActive */
        $variationIsActive = pluginApp(VariationIsActive::class);
        $container->addFilterBuilder($variationIsActive);

        return $container;
    }

    public function getCustomFilterContainer(): CatalogFilterBuilderContainer
    {
        //Todo add custom filter example
        return pluginApp(CatalogFilterBuilderContainer::class);
    }

    public function isPreviewable(): bool
    {
        // If you are not sure what this does please check the guide for DynamicConfig before setting this to true
        // In your productive plugin
        return true;
    }

    public function getDynamicConfig(): CatalogDynamicConfigContract
    {
        return new ExampleDynamicConfig();
    }
}