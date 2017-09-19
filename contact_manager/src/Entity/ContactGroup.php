<?php

namespace Drupal\contact_manager\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Contact group entity.
 *
 * @ConfigEntityType(
 *   id = "contact_group",
 *   label = @Translation("Contact group"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contact_manager\ContactGroupListBuilder",
 *     "form" = {
 *       "add" = "Drupal\contact_manager\Form\ContactGroupForm",
 *       "edit" = "Drupal\contact_manager\Form\ContactGroupForm",
 *       "delete" = "Drupal\contact_manager\Form\ContactGroupDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\contact_manager\ContactGroupHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "contact_group",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/contact_group/{contact_group}",
 *     "add-form" = "/admin/content/contact_group/add",
 *     "edit-form" = "/admin/content/contact_group/{contact_group}/edit",
 *     "delete-form" = "/admin/content/contact_group/{contact_group}/delete",
 *     "collection" = "/admin/content/contact_group"
 *   }
 * )
 */
class ContactGroup extends ConfigEntityBase implements ContactGroupInterface {

  /**
   * The Contact group ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Contact group label.
   *
   * @var string
   */
  protected $label;

}
