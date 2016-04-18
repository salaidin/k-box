<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Search page Language Lines
	|--------------------------------------------------------------------------
	|
	|
	*/

	'page_title' => 'Поиск',


	'form' => array(
		'placeholder' => 'Поиск',
        'placeholder_in' => 'Искать в ":location"',
		'hint' => 'Поиск слов и фраз. Вы также можете использовать союзы AND и/или OR для более содержательного поиска.',
		'hint_in' => 'Искать в :location',
		'submit' => 'Начните поиск',
		'public_switch_alt' => 'Поиск среди документов, доступных во Внешней сети K-LINK',
		'private_switch_alt' => 'Поиск среди закрытых документов',
	),

	'error' => 'Возникла ошибка при поиске в подключении к корневому элементу K-LINK. Команда K-LINK информирована и работает над этим.',

	'empty_query' => 'Введите ключевые слова в поле поиска и нажмите кнопку ввода для начала поиска.',

	'loading_filters' => 'Загрузка фильтров...',

	'no_results' => 'Извините, по запросу <strong>:term</strong> в <strong>:collection</strong> не было найдено документов.',
	'no_results_no_markup' => 'Извините, по запросу :term в :collection не было найдено документов.',

	'try_message' => 'Попробуйте поискать слова, начинающиеся с :startwithlink',


	'facets' => array(
		'institutionId' => 'Организация',
		'language' => "Язык",
        'documentType' => "Тип документа",
        'documentGroups' => "Коллекции",
	),

];
