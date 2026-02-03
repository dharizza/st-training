<?php

declare(strict_types=1);

namespace Drupal\audit\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityDeleteEvent;
use Drupal\content_moderation\Entity\ContentModerationStateInterface;

/**
 * @todo Add description for this subscriber.
 */
final class EntityDeletionSubscriber implements EventSubscriberInterface {

  /**
   * Entity delete event handler.
   */
  public function logDeletion(EntityDeleteEvent $event): void {
    $deleted_entity = $event->getEntity();

    if ($deleted_entity instanceof ContentModerationStateInterface) {
      return ;
    }

    $entity_type = $deleted_entity->getEntityTypeId();
    $bundle = $deleted_entity->bundle();
    $id = $deleted_entity->id();

    $data = [
      'label' => $entity_type . ' - ' . $bundle . ' with ID = ' . $id . ' ' . $deleted_entity->label(),
      'deleted' => time(),
      'deleted_by' => \Drupal::currentUser()->id(),
      'entity_type' => $entity_type,
      'bundle' => $bundle,
      // 'created',
      // 'changed',
      // 'deleted_entity_author',
    ];

    $record = \Drupal::entityTypeManager()->getStorage('deletion_record')->create($data);
    $record->save();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_DELETE => ['logDeletion']
    ];
  }

}
