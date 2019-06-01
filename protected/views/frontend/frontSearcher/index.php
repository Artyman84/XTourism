<?

    /* Module */
    $depCities = $searcher->depCities(false);

    $this->widget(
        'tsearch.searchers.TSearcherStandard',
        [
            'user_id' => $uid,
            'iframe_id' => $if_id,
            'bg_color_class' => $settings->bg_color_class,
            'spinner' => $settings->spinner,
            'rounding' => $settings->rounding,
            'params' => [
                'depCities' => [
                    'listOptions' => ['id' => 'from'],
                    'data' => CHtml::listData($depCities, 'id', 'name'),
                    'selectedId' => $settings->depCity
                ],
                'countries' => [
                    'listOptions' => ['id' => 'where'],
                    'data' => $searcher->countries($settings->depCity ? $settings->depCity : $depCities[0]->id, true),
                    'selectedId' => $settings->country
                ],
                'hotelCategories' => [
                    'data' => $searcher->hotelCategories(),
                    'selectedId' => $settings->hotelCategory,
                    'more' => $settings->hotelCategoryMore
                ],
                'durations' => [
                    'selectedNightFrom' => $settings->nightFrom,
                    'selectedNightTo' => $settings->nightTo
                ],
                'price' => [
                    'minPrice' => $settings->minPrice,
                    'maxPrice' => $settings->maxPrice
                ],
                'currency' => [
                    'isCU' => $settings->currency,
                ],
                'people' => [
                    'selAdults' => $settings->adults,
                    'selChildren' => $settings->children,
                    'selChild1' => $settings->child1,
                    'selChild2' => $settings->child2,
                    'selChild3' => $settings->child3
                ],
                'meals' => [
                    'data' => $searcher->meals(),
                    'selectedId' => $settings->meals,
                    'more' => $settings->mealsMore,
                ]
            ]
        ]
    ); ?>
