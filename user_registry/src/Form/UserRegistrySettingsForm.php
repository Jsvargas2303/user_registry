<?php

namespace Drupal\user_registry\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class UserRegistrySettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['user_registry.settings'];
  }

  public function getFormId() {
    return 'user_registry_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('user_registry.settings');

    $form['full_name_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name Label'),
      '#default_value' => $config->get('full_name_label') ?: 'Full Name',
    ];
    $form['email_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Label'),
      '#default_value' => $config->get('email_label') ?: 'Email',
    ];
    $form['dni_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('DNI Label'),
      '#default_value' => $config->get('dni_label') ?: 'DNI',
    ];
    $form['birth_date_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Birth Date Label'),
      '#default_value' => $config->get('birth_date_label') ?: 'Birth Date',
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('user_registry.settings')
      ->set('full_name_label', $form_state->getValue('full_name_label'))
      ->set('email_label', $form_state->getValue('email_label'))
      ->set('dni_label', $form_state->getValue('dni_label'))
      ->set('birth_date_label', $form_state->getValue('birth_date_label'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
