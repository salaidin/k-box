<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use KBox\DocumentDescriptor;
use KBox\Option;
use Ramsey\Uuid\Uuid;
use KBox\Console\Commands\DmsUpdateCommand;

class DmsUpdateCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function verify_that_uuid_are_added_to_existing_document_descriptors()
    {
        $this->withKlinkAdapterMock();

        $docs = factory('KBox\DocumentDescriptor', 11)->create(['uuid' => "00000000-0000-0000-0000-000000000000"]);
        $v3_docs = factory('KBox\DocumentDescriptor')->create(['uuid' => "39613931-3436-3066-2d31-3533322d3466"]);

        $doc_ids = $docs->pluck('id')->toArray();
        
        // making sure that the install script thinks an update must be performed
        Option::create(['key' => 'c', 'value' => ''.time()]);

        $count_with_null_uuid = DocumentDescriptor::local()->withNullUuid()->count();

        $this->assertEquals($docs->count(), $count_with_null_uuid, 'Query cannot retrieve descriptors with null UUID');

        $command = new DmsUpdateCommand();

        $updated = $this->invokePrivateMethod($command, 'generateDocumentsUuid');

        $this->assertEquals(12, $updated, 'Not all documents have been updated');
        
        $ret = DocumentDescriptor::local()->whereIn('id', array_merge($doc_ids, [$v3_docs->id]))->get();

        $this->assertEquals(12, $ret->count(), 'Not found the same documents originally created');

        $ret->each(function ($f) {
            $this->assertTrue(Uuid::isValid($f->uuid));
            $this->assertEquals(4, Uuid::fromString($f->uuid)->getVersion());
        });

        //second invokation of the same command

        $updated = $this->invokePrivateMethod($command, 'generateDocumentsUuid');

        $this->assertEquals(0, $updated, 'Some UUID has been regenerated');
    }
}
