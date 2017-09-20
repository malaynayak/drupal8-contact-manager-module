<?php

namespace Drupal\contact_manager\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact entities.
 *
 * @ingroup contact_manager
 */
interface ContactInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Contact name.
   *
   * @return string
   *   Name of the Contact.
   */
  public function getName();

  /**
   * Sets the Contact name.
   *
   * @param string $name
   *   The Contact name.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setName($name);

  /**
   * Gets the Contact Primary Phone Number.
   *
   * @return string
   *   Primary Phone Number of the Contact.
   */
  public function getPhoneNumber();

  /**
   * Sets the Contact primary phone number.
   *
   * @param string $phone_number
   *   The Contact primary phone number.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setPhoneNumber($phone_number);

  /**
   * Gets the Contact email.
   *
   * @return string
   *   Email of the Contact.
   */
  public function getEmail();

  /**
   * Sets the Contact email.
   *
   * @param string $email
   *   The Contact email.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setEmail($email);

  /**
   * Gets the Contact address.
   *
   * @return string
   *   Address of the Contact.
   */
  public function getAddress();

  /**
   * Sets the Contact address.
   *
   * @param string $address
   *   The Contact address.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setAddress($address);

  /**
   * Gets the Contact creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contact.
   */
  public function getCreatedTime();

  /**
   * Sets the Contact creation timestamp.
   *
   * @param int $timestamp
   *   The Contact creation timestamp.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Contact published status indicator.
   *
   * Unpublished Contact are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Contact is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Contact.
   *
   * @param bool $published
   *   TRUE to set this Contact to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\contact_manager\Entity\ContactInterface
   *   The called Contact entity.
   */
  public function setPublished($published);

}
