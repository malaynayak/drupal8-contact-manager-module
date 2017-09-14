<?php

namespace Drupal\contact_manager\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ContactGroupForm.
 */
class ContactGroupForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $contact_group = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $contact_group->label(),
      '#description' => $this->t("Label for the Contact group."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $contact_group->id(),
      '#machine_name' => [
        'exists' => '\Drupal\contact_manager\Entity\ContactGroup::load',
      ],
      '#disabled' => !$contact_group->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $contact_group = $this->entity;
    $status = $contact_group->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Contact group.', [
          '%label' => $contact_group->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Contact group.', [
          '%label' => $contact_group->label(),
        ]));
    }
    $form_state->setRedirectUrl($contact_group->toUrl('collection'));
  }

}
