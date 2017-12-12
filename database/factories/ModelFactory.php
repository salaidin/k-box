<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(KBox\User::class, function (Faker\Generator $faker) {
    $institution_count = \KBox\Institution::count();
    
    if ($institution_count == 0) {
        $institution = factory(KBox\Institution::class)->create()->id;
    } else {
        $institution = \KBox\Institution::all()->random()->id;
    }

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'institution_id' => $institution
    ];
});

$factory->defineAs(KBox\User::class, 'admin', function (Faker\Generator $faker) {
    $institution_count = \KBox\Institution::count();
    
    if ($institution_count == 0) {
        $institution = factory(KBox\Institution::class)->create()->id;
    } else {
        $institution = \KBox\Institution::all()->random()->id;
    }
    
    return [
        'name' => 'admin',
        'email' => 'admin@klink.local',
        'password' => bcrypt(str_random(10)),
        'institution_id' => $institution
    ];
});

$factory->define(KBox\File::class, function (Faker\Generator $faker) {
    $hash = $faker->sha256.''.$faker->sha256;

    if(!is_dir(storage_path('documents'))){
        mkdir(storage_path('documents'));
    }

    copy(base_path('tests/data/example.pdf'), storage_path('documents/example.pdf'));
    $path = storage_path('documents/example.pdf');
    
    return [
        'name' => $faker->sentence,
        'hash' => hash_file('sha512', $path),
        'path' => $path,
        'mime_type' => 'application/pdf',
        'user_id' => function () {
            return factory(KBox\User::class)->create()->id;
        },
        'size' => $faker->randomNumber(2),
        'original_uri' => '',
        'upload_completed_at' => \Carbon\Carbon::now()
    ];
});

$factory->define(KBox\Institution::class, function (Faker\Generator $faker) {
    return [
        'klink_id' => str_random(4),
        'email' => $faker->email,
        'url' => $faker->url,
        'type' => 'Organization',
        'thumbnail_uri' => $faker->imageUrl,
        'phone' => $faker->sentence,
        'address_street' => $faker->sentence,
        'address_country' => $faker->sentence,
        'address_locality' => $faker->sentence,
        'address_zip' => $faker->sentence,
        'name' => $faker->sentence
    ];
});

$factory->define(KBox\DocumentDescriptor::class, function (Faker\Generator $faker) {
    $hash = $faker->sha256.''.$faker->sha256;
    
    $user = factory(KBox\User::class)->create();
    
    $file = factory(KBox\File::class)->create([
        'user_id' => $user->id,
        'original_uri' => '',
        'upload_completed_at' => \Carbon\Carbon::now()
    ]);
    
    $institution_count = \KBox\Institution::count();
    
    if ($institution_count == 0) {
        $institution = factory(KBox\Institution::class)->create()->id;
    } else {
        $institution = \KBox\Institution::all()->random()->id;
    }
    
    return [
        'institution_id' => $institution,
        'local_document_id' => substr($hash, 0, 6),
        'title' => $faker->sentence,
        'hash' => $hash,
        'document_uri' => $faker->url,
        'thumbnail_uri' => $faker->imageUrl,
        'mime_type' => 'application/pdf',
        'visibility' => 'private',
        'document_type' => 'document',
        'user_owner' => 'some user <usr@user.com>',
        'user_uploader' => 'some user <usr@user.com>',
        'abstract' => $faker->paragraph,
        'language' => $faker->languageCode,
        'file_id' => $file->id,
        'owner_id' => $user->id,
        'status' => KBox\DocumentDescriptor::STATUS_COMPLETED,
    ];
});

$factory->define(KBox\Starred::class, function (Faker\Generator $faker) {
    return [
      'user_id' => factory(KBox\User::class)->create()->id,
      'document_id' => factory(KBox\DocumentDescriptor::class)->create()->id
    ];
});

$factory->define(KBox\Import::class, function (Faker\Generator $faker) {
    return [
        'bytes_expected' => 0,
        'bytes_received' => 0,
        'is_remote' => true,
        'file_id' => 'factory:KBox\File',
        'status' => KBox\Import::STATUS_QUEUED,
        'user_id' => 'factory:KBox\User',
        'parent_id' => null,
        'status_message' => KBox\Import::MESSAGE_QUEUED
    ];
});

$factory->define(KBox\Project::class, function (Faker\Generator $faker) {
    $institution_count = \KBox\Institution::count();
    
    if ($institution_count == 0) {
        $institution = factory(KBox\Institution::class)->create()->id;
    } else {
        $institution = \KBox\Institution::all()->random()->id;
    }
    
    $user = factory(KBox\User::class)->create([
        'institution_id' => $institution
    ]);
    
    $user->addCapabilities(KBox\Capability::$PROJECT_MANAGER_NO_CLEAN_TRASH);
    
    $project_title = $faker->sentence;
        
    $service = app('Klink\DmsDocuments\DocumentsService');
        
    $collection = $service->createGroup($user, $project_title, null, null, false);
    
    return [
        'name' => $project_title,
        'user_id' => $user->id,
        'collection_id' => $collection->id,
    ];
});

$factory->define(Klink\DmsMicrosites\Microsite::class, function (Faker\Generator $faker) {
    $project = factory(KBox\Project::class)->create();
    
    $project_manager = $project->manager()->first();
    
    return [
        'project_id' => $project->id,
        'title' => $faker->sentence,
        'slug' => $faker->slug,
        'description' => $faker->paragraph,
        'logo' => str_replace('http://', 'https://', $faker->imageUrl),
        'hero_image' => str_replace('http://', 'https://', $faker->imageUrl),
        'default_language' => 'en',
        'user_id' => $project_manager->id,
        'institution_id' => $project_manager->institution_id,
    ];
});

//
// $factory('KBox\RecentSearch', [
//   'terms' => $faker->word,
//   'times' => $faker->numberBetween(1, 10),
//   'user_id' => 'factory:KBox\User'
// ]);
//
// $factory('KBox\PeopleGroup', [
//   'name' => $faker->word,
//   'is_institution_group' => $faker->randomElement(array(true, false)),
//   'user_id' => 'factory:KBox\User'
// ]);
//
$factory->define(KBox\Shared::class, function (Faker\Generator $faker) {
    return [
      'user_id' => function () {
          return factory(KBox\User::class)->create()->id;
      },
      'token' => $faker->md5,
      'shareable_id' => function () {
          return factory(KBox\DocumentDescriptor::class)->create()->id;
      },
      'shareable_type' => 'KBox\DocumentDescriptor',
      'sharedwith_id' => function () {
          return factory(KBox\User::class)->create()->id;
      },
      'sharedwith_type' => 'KBox\User'
    ];
});

$factory->define(KBox\PublicLink::class, function (Faker\Generator $faker) {
    return [
      'user_id' => function () {
          return factory(KBox\User::class)->create()->id;
      },
      'slug' => $faker->slug,
    ];
});

$factory->defineAs(KBox\Shared::class, 'publiclink', function (Faker\Generator $faker) {
    $link = factory(KBox\PublicLink::class)->create();

    return [
      'user_id' => $link->user_id,
      'token' => $faker->md5,
      'shareable_id' => function () {
          return factory(KBox\DocumentDescriptor::class)->create()->id;
      },
      'shareable_type' => 'KBox\DocumentDescriptor',
      'sharedwith_id' => $link->id,
      'sharedwith_type' => 'KBox\PublicLink'
    ];
});
