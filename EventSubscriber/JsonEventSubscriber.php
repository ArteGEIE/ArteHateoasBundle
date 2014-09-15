<?php

namespace Arte\Bundle\HateoasBundle\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Exception\LogicException;
use Arte\Bundle\HateoasBundle\Adder\AdderRegistry;

class JsonEventSubscriber implements EventSubscriberInterface
{
    protected $adderRegistry;

    public function __construct(AdderRegistry $adderRegistry)
    {
        $this->adderRegistry = $adderRegistry;
    }

    /**
     * @{inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize'
            )
        );
    }

    /**
     * @param ObjectEvent $event
     * @throws \JMS\Serializer\Exception\LogicException
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        if ($adder = $this->adderRegistry->retrieve($event->getObject(), $event->getContext())) {
            $adder->add($event->getObject(), $event->getVisitor());
        }
    }
}
