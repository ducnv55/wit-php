<?php

namespace spec\Tgallice\Wit\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tgallice\Wit\Model\Context;
use Tgallice\Wit\Model\Entity;
use Tgallice\Wit\Model\Location;

class ContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\Model\Context');
    }

    function it_has_a_reference_date()
    {
        $date = new \DateTimeImmutable();

        $this->beConstructedWith([
            'reference_date' => $date,
        ]);
        $this->getReferenceDate()->shouldReturn($date->format(DATE_ISO8601));
    }

    function it_has_a_default_reference_date()
    {
        $this->getReferenceDate()->shouldContain((new \DateTime())->format(DATE_ISO8601));
    }

    function it_has_no_default_location()
    {
        $this->getLocation()->shouldReturn([]);
    }

    function it_can_define_a_location()
    {
        $location = new Location(1.1, 1.2);

        $this->beConstructedWith([
            'location' => $location,
        ]);
        $this->getLocation()->shouldReturn($location);
    }

    function it_has_no_default_state()
    {
        $this->getState()->shouldReturn(null);
    }

    function it_can_define_a_state()
    {
        $this->beConstructedWith([
            'state' => 'state',
        ]);
        $this->getState()->shouldReturn('state');
    }

    function it_has_no_default_entities()
    {
        $this->getEntities()->shouldReturn([]);
    }

    function it_can_define_entities()
    {
        $entity = new Entity('id');
        
        $this->beConstructedWith([
            'entities' => [$entity],
        ]);
        $this->getEntities()->shouldReturn([$entity]);
    }

    function it_has_no_default_timezone()
    {
        $this->getTimezone()->shouldReturn(null);
    }

    function it_can_define_timezone()
    {
        $this->beConstructedWith([
            'timezone' => 'timezone',
        ]);
        $this->getTimezone()->shouldReturn('timezone');
    }

    function it_can_add_custom_context_field()
    {
        $context = $this->add('context', 'value');
        $context->get('context')->shouldReturn('value');
    }

    function it_can_remove_a_context_field()
    {
        $context = $this->add('context', 'value');
        $context->get('context')->shouldReturn('value');

        $context = $this->remove('context');
        $context->get('context')->shouldReturn(null);
    }

    function it_can_check_presence_of_a_context_field()
    {
        $context = $this->add('context', 'value');
        $context->has('context')->shouldReturn(true);
        $context->has('wrong_context')->shouldReturn(false);
    }

    function it_is_immutable()
    {
        $context = $this->add('context', 'value');
        $newContext = $context->add('context', 'value');
        $newContext2 = $newContext->remove('context');

        $context->shouldHaveType(Context::class);
        $context->shouldBeLike($newContext);
        $context->shouldNotBeEqualTo($newContext);
        $newContext->get('context')->shouldReturn('value');
        $newContext2->has('context')->shouldReturn(false);
    }

    function it_must_be_json_serializable()
    {
        $this->shouldHaveType(\JsonSerializable::class);
        $serialized = json_encode($this->getWrappedObject());
        $this->jsonSerialize()->shouldReturn(json_decode($serialized, true));
    }
}
