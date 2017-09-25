<?php

namespace Drupal\contact_manager;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal;

/**
 * Defines a class to build a listing of Contact entities.
 *
 * @ingroup contact_manager
 */
class ContactListBuilder extends EntityListBuilder {

  protected $limit = 10;

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entity_query = Drupal::service('entity.query')->get('contact');
    $header = $this->buildHeader();
    $entity_query->pager($this->limit);
    $entity_query->tableSort($header);
    $entity_ids = $entity_query->execute();
    return $this->storage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = [
      'data' => $this->t('Name'),
      'field' => 'name',
      'specifier' => 'name'
    ];
    $header['photo'] = $this->t('Photo');
    $header['group'] = [
      'data' => $this->t('Group'),
      'field' => 'contact_group_id',
      'specifier' => 'contact_group_id'
    ];
    $header['phone_number'] = $this->t('Phone Number');
    $header['email'] = [
      'data' => $this->t('Email'),
      'field' => 'email',
      'specifier' => 'email'
    ];
    $header['owner'] = $this->t('Owner');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\contact_manager\Entity\Contact */
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.contact.canonical',
      ['contact' => $entity->id()]
    );
    $row['photo'] = [
      'data' => [
        '#type' => 'html_tag',
        '#tag' => 'img',
        '#attributes' => ['src' => $entity->getContactPhoto('tiny')]
      ]
    ];
    $row['group'] = $entity->getContactGroupName();
    $row['phone_number'] = Link::fromTextAndUrl(
      $entity->getPhoneNumber(),
      Url::fromUri('tel:'.$entity->getPhoneNumber())
    );
    $row['email'] = Link::fromTextAndUrl(
      $entity->getEmail(),
      Url::fromUri('mailto:'.$entity->getPhoneNumber())
    );
    $row['owner'] = Link::createFromRoute(
      $entity->getOwner()->getUsername(),
      'entity.user.canonical',
      ['user' => $entity->getOwner()->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
