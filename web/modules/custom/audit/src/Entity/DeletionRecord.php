<?php

declare(strict_types=1);

namespace Drupal\audit\Entity;

use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Form\DeleteMultipleForm;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\audit\DeletionRecordAccessControlHandler;
use Drupal\audit\DeletionRecordInterface;
use Drupal\audit\DeletionRecordListBuilder;
use Drupal\audit\Form\DeletionRecordForm;
use Drupal\user\EntityOwnerTrait;
use Drupal\views\EntityViewsData;

/**
 * Defines the deletion record entity class.
 */
#[ContentEntityType(
  id: 'deletion_record',
  label: new TranslatableMarkup('Deletion Record'),
  label_collection: new TranslatableMarkup('Deletion Records'),
  label_singular: new TranslatableMarkup('deletion record'),
  label_plural: new TranslatableMarkup('deletion records'),
  entity_keys: [
    'id' => 'id',
    'label' => 'label',
    'owner' => 'uid',
    'uuid' => 'uuid',
  ],
  handlers: [
    'list_builder' => DeletionRecordListBuilder::class,
    'views_data' => EntityViewsData::class,
    'access' => DeletionRecordAccessControlHandler::class,
    'form' => [
      'add' => DeletionRecordForm::class,
      'edit' => DeletionRecordForm::class,
      'delete' => ContentEntityDeleteForm::class,
      'delete-multiple-confirm' => DeleteMultipleForm::class,
    ],
    'route_provider' => [
      'html' => AdminHtmlRouteProvider::class,
    ],
  ],
  links: [
    'collection' => '/admin/content/deletion-record',
    'add-form' => '/deletion-record/add',
    'canonical' => '/deletion-record/{deletion_record}',
    'edit-form' => '/deletion-record/{deletion_record}/edit',
    'delete-form' => '/deletion-record/{deletion_record}/delete',
    'delete-multiple-form' => '/admin/content/deletion-record/delete-multiple',
  ],
  admin_permission: 'administer deletion_record',
  base_table: 'deletion_record',
  label_count: [
    'singular' => '@count deletion records',
    'plural' => '@count deletion records',
  ],
)]
class DeletionRecord extends ContentEntityBase implements DeletionRecordInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the deletion record was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the deletion record was last edited.'));

    return $fields;
  }

}
