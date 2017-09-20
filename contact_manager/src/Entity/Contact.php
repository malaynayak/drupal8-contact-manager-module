<?php

namespace Drupal\contact_manager\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use ContactGroupInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal;

/**
 * Defines the Contact entity.
 *
 * @ingroup contact_manager
 *
 * @ContentEntityType(
 *   id = "contact",
 *   label = @Translation("Contact"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contact_manager\ContactListBuilder",
 *     "views_data" = "Drupal\contact_manager\Entity\ContactViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\contact_manager\Form\ContactForm",
 *       "add" = "Drupal\contact_manager\Form\ContactForm",
 *       "edit" = "Drupal\contact_manager\Form\ContactForm",
 *       "delete" = "Drupal\contact_manager\Form\ContactDeleteForm",
 *     },
 *     "access" = "Drupal\contact_manager\ContactAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\contact_manager\ContactHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contact",
 *   data_table = "contact_field_data",
 *   admin_permission = "administer contact entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/contact/{contact}",
 *     "add-form" = "/admin/content/contact/add",
 *     "edit-form" = "/admin/content/contact/{contact}/edit",
 *     "delete-form" = "/admin/content/contact/{contact}/delete",
 *     "collection" = "/admin/content/contact",
 *   },
 *   field_ui_base_route = "contact.settings"
 * )
 */
class Contact extends ContentEntityBase implements ContactInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhoneNumber() {
    return $this->get('phone_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPhoneNumber($phone_number) {
    $this->set('phone_number', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email) {
    $this->set('email', $email);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAddress() {
    return $this->get('address')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAddress($address) {
    $this->set('address', $address);
    return $this;
  }

  /**
   * Gets contact photo
   *
   * @param string $style
   *   A valid image style e.g large, thumbnail
   *
   * @return string
   *   The url of the image
   */
  public function getContactPhoto($style = null) {
    $field_contact_photo_fid = $this->get('field_contact_photo')->target_id;
    if(!empty($field_contact_photo_fid)){
      $file_uri = File::load($field_contact_photo_fid)->getFileUri();
      if(empty($style)){
        return file_create_url($file_uri);
      } else {
        $image_style = Drupal::entityTypeManager()->getStorage('image_style')->load($style);
        if(!empty($image_style)){
          return $image_style->buildUrl($file_uri);
        } else {
          return file_create_url($file_uri);
        }
      }
    }
    return null;
  }

  /**
   * {@inheritdoc}
   */
  public function getContactGroup() {
    return $this->get('contact_group_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setContactGroup(ContactGroupInterface $contact_group) {
    $this->set('contact_group_id', $contact_group->id());
    return $this;
  }

  /**
   * Get contact group of the contact
   *
   * @return string
   *   contact group name of the contact
   */
  public function getContactGroupName() {
    return $this->getContactGroup()->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['contact_group_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Contact Group.'))
      ->setDescription(t('Select a contact group'))
      ->setSetting('target_type', 'contact_group')
      ->setSetting('handler', 'default')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('Enter contact name.'))
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setDisplayOptions('view', ['label' => 'inline','type' => 'string'])
      ->setDisplayOptions('form', ['type' => 'string_textfield'])
      ->setPropertyConstraints('value', ['Length' => ['max' => 50]])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
      
    $fields['phone_number'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Primary Phone Number'))
      ->setDescription(t('Enter primary phone number here.'))
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setDefaultValue('')
      ->setDisplayOptions('view', ['label' => 'inline','type' => 'telephone_link'])
      ->setDisplayOptions('form', ['type' => 'telephone_default'])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email Id'))
      ->setDescription(t('Enter email address.'))
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', ['label' => 'inline','type' => 'basic_string'])
      ->setDisplayOptions('form', ['type' => 'email_default'])
      ->setPropertyConstraints('value', ['Length' => ['max' => 50]])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address'))
      ->setDescription(t('Enter address.'))
      ->setRevisionable(TRUE)
      ->setPropertyConstraints('value', ['Length' => ['max' => 200]])
      ->setDisplayOptions('view', ['label' => 'inline','type' => 'string'])
      ->setDisplayOptions('form', ['type' => 'string_textfield'])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDescription(t('A boolean indicating whether the Contact is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
