<?php

namespace Drupal\contact_manager;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Contact entities.
 *
 * @ingroup contact_manager
 */
class ContactListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['photo'] = $this->t('Photo');
    $header['group'] = $this->t('Group');
    $header['phone_number'] = $this->t('Phone Number');
    $header['email'] = $this->t('Email');
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
