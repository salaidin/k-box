<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Document and Document Descriptor Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are used for localizing the document description
	| meta information and the document administration menu and title
	|
	*/

	'descriptor' => array(

		'owned_by' => 'владелец',
		'language' => 'язык',
		'added_on' => 'добавлено',
		'last_modified' => 'Изменено',
		'indexing_error' => 'Документ не был индексирован в K-LINK',
		'private' => 'Личные',
		'shared' => 'Совместный доступ',
		'is_public' => 'Открытый доступ',
		'is_public_description' => 'Документ доступен для других Организаций в сети K-LINK',
		'trashed' => 'Документ находится в корзине',
        'klink_public_not_mine' => 'Нельзя внести изменения, т.к. данный файл является всего лишь ссылкой на документ во открытомем доступе в K-Link сети.',
	),

	'page_title' => 'Документы',

	'menu' => array(
		'all' => 'Все',
		'public' => 'Открытые в K-Link сети',
		'private' => 'Закрытые',
		'personal' => 'Личные',
		'starred' => 'Со звездочкой',
		'shared' => 'Доступные мне',
		'recent' => 'Недавние',
		'trash' => 'Корзина',
		'not_indexed' => 'Неиндексированные',
		'recent_hint' => 'Здесь показаны последние ваши рабочие документы',
        'starred_hint' => 'Здесь показаны ваши важные или интересные вам документы',
	),


	'visibility' => array(
		'public' => 'Открытые',
		'private' => 'Закрытые',
	),

	'type' => array(
		// See here for better understanding of the russian translation rules https://github.com/symfony/symfony/issues/8698
		// 'нет яблок|есть одно яблоко|есть %count% яблока|есть %count% яблок'
		// no apples | have one apple | have %count% apples | have %count% apples
		// 0 | 1-4 | 5+
		// 21 | 22-24 | 25+
        /**
		
		- If the number is 1, or the number ends in the word 1 (example: 1, 21, 61) (but not 11), then you should use the first case
		- If the number, or the last digit of the number is 2, 3 or 4, (example: 22, 42, 103, 4) (but not 12, 13 & 14), then you should use the second case
		- If the number ends in any other digit you should use the 3rd case. All the 'teens'  fit in to this catagory (11, 12, 13, 14, 15,16,17,18,19). Any number ending with 0 (including 0 itself) also fits into this category
*/
		'web-page' => 'веб страница|веб страницы|веб страниц',
		'document' => 'документ|документа|документов',  
		'spreadsheet' => 'таблица|таблицы|таблиц',
		'presentation' => 'презентация|презентации|презентаций',
		'uri-list' => 'URL список|URL списка|URL списков',
		'image' => 'рисунок|рисунка|рисунков',
		'geodata' => 'геоданные|геоданных|геоданных',
        'text-document' => 'текстовый документ|текстовых документа|текстовых документов',
	),

	'empty_msg' => 'Нет документов в <strong>:context</strong>',

	'bulk' => array(

		'removed' => ':num элемент удален. Вы можете найти его в корзине.|:num элемента удалено. Вы можете найти их в корзине.|:num элементов удалено. Вы можете найти их в корзине.',
		
		'permanently_removed' => ':num элемент удален безвозвратно.|:num элемента удалено безвозвратно.|:num элементов удалено безвозвратно.',
		
		'restored' => ':num элемент восстановлен.|:num элемента восстановлено.|:num элементов восстановлено.',

		'remove_error' => 'Невозможно удалить элементы. Ни один документ или коллекция не были удалены. :error',
		
        'copy_error' => 'Невозможно копировать в коллекцию. :error',
		
        'copy_completed_all' => 'Все документы были добавлены в :collection',
		
        // used when not all the documents you were adding to a collection has been added
        'copy_completed_some' => '{0}Ни один документ не был добавлен, т.к. они уже хранились в ":collection"|[1,Inf] Добавленых документов :count, оставшиеся :remaining уже находились в :collection',
        
		'restore_error' => 'Невозможно восстановить документ. :error',
		
		'make_public' => ':num документ находится в открытом доступе в сети K-LINK|:num документа находится в открытом доступе в сети K-LINK.|:num документов находится в открытом доступе в сети K-LINK.',
		
		'make_public_error' => 'Операция предоставления открытого доступа не была завершена в связи с ошибкой. :error',

	),

	'create' => array(
		'page_breadcrumb' => 'Создать',
		'page_title' => 'Создать новый документ',
	),

	'edit' => array(
		'page_breadcrumb' => 'Изменить :document',
		'page_title' => 'Изменить :document',

		'title_placeholder' => 'Название документа',

		'abstract_label' => 'Краткое содержание',
		'abstract_placeholder' => 'Краткое содержание',

		'authors_label' => 'Авторы',
		'authors_help' => 'Авторы должны быть указаны через запятую в следующем формате <code>имя фамилия &lt;mail@something.com&gt;</code>',

		'authors_placeholder' => 'Авторы документа (имя фамилия <mail@something.com>)',
		'language_label' => 'Язык',

		'last_edited' => 'Последнее изменение <strong>:time</strong>',
		'created_on' => 'Создано <strong>:time</strong>',
		'uploaded_by' => 'Загружено <strong>:name</strong>',

		'public_visibility_description' => 'Данный документ будет доступен для всех участников Сети K-LINK',
		
		
		'not_index_message' => 'Документ недоступен в сети K-LINK. Пожалуйста, попробуйте <button type="submit">переиндексировать его</button> сейчас или свяжитесь с вашим администратором.',
	),

	'update' => array(
		'error' => 'Невозможно обновить этот документ. Не было никаких изменений. :error',
	),


	'preview' => array(
		'page_title' => 'Предварительный просмотр :document',
		'error' => 'Извините, мы не смогли загрузить предварительный просмотр ":document".',
		'not_available' => 'Предварительный  просмотр невозможен для данного документа.',
		'google_file_disclaimer' => ':document это файл с Google Диска, поэтому вы не можете просмотреть его здесь. Откройте его на Google Диске.',
    	'open_in_google_drive_btn' => 'Открыть в Google Диске',
	),

	'versions' => array(

		'section_title' => 'Версии документов',

		'section_title_with_count' => '1 Версия документа|:number Версии документов|:number Версий документа',

		'version_count_label' => ':number версия|:number версии|:number версий',

		'version_number' => 'версия :number',

		'version_current' => 'текущая',

		'new_version_button' => 'Загрузить новую версию',
		
		'new_version_button_uploading' => 'Загрузка документа...',

		'filealreadyexists' => 'Версия загружаемого вами документа уже существует в системе',
	),

	'messages' => array(
		'updated' => 'Документ обновлен',
		'local_public_only' => 'В настоящее время показаны только открытыеие документы Организации.',
		'forbidden' => 'У вас нет прав для изменения документа.',
		'delete_forbidden' => 'Вы не можете удалять документы. Пожалуйста, свяжитесь с Проектным менеджером или с Администратором.',
		'delete_public_forbidden' => 'Вы не можете удалить документ, находящийся в открытом доступе. Пожалуйста, свяжитесь с K-LINKером или с Администратором.',
		'delete_force_forbidden' => 'Вы не можете безвозвратно удалить документ. Пожалуйста, свяжитесь с Проектным менеджером или с Администратором.',
		'drag_hint' => 'Перетащите файлы сюда для начала загрузки.',
		'recent_hint_dms_manager' => 'Вы видите все обновления документов, введенные каждым пользователем системы.',
        'no_documents' => 'Нет документов. Вы можете загрузить новые документы, перетаскивая их сюда или используя "Создать или добавить" в верхнем навигационном поле.',
	),
];
