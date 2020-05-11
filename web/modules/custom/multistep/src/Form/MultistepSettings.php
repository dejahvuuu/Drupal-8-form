<?php

namespace Drupal\multistep\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Define multistep settings.
 */
class MultistepSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'multistep.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multistep_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('multistep.settings');

    $form["#tree"] = TRUE;
    $form['bootstrap'] = [
      '#type' => 'vertical_tabs',
      '#prefix' => '<h2><small>' . t('Multistep Settings') . '</small></h2>',
      '#weight' => -10,
      '#default_tab' => $config->get('active_tab'),
    ];
    $group = "first_step";
    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('First Step'),
      '#open' => TRUE,
      '#group' => 'bootstrap',
      '#weight' => 5,
    ];
    $form[$group]['first_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Form Title"),
      '#default_value' => isset($config->get($group)['first_title']) ? $config->get($group)['first_title'] : 'Login App',
      '#maxlength' => 100,
      '#description' => $this->t("Write a title for the form"),
    ];
    $form[$group]['first_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t("Text description about form"),
      '#format' => 'full_html',
      '#default_value' => isset($config->get($group)['first_message']['value']) ? $config->get($group)['first_message']['value'] : 'Welcome to <strong>login app</strong>, please insert your info for register in platform.',
    ];
    $form[$group]['url_first_step'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Url redirect"),
      '#default_value' => isset($config->get($group)['url_first_step']) ? $config->get($group)['url_first_step'] : '/second-step',
      '#maxlength' => 100,
      '#description' => $this->t("Write a URL for redirect"),
    ];
    $group = "second_step";
    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('Second Step'),
      '#open' =>  FALSE,
      '#group' => 'bootstrap',
      '#weight' => 6,
    ];
    $form[$group]['second_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Form Title"),
      '#default_value' => isset($config->get($group)['second_title']) ? $config->get($group)['second_title'] : 'Login App',
      '#maxlength' => 100,
      '#description' => $this->t("Write a title for the form"),
    ];
    $form[$group]['second_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t("Text description about form"),
      '#format' => 'full_html',
      '#default_value' => isset($config->get($group)['second_message']['value']) ? $config->get($group)['second_message']['value'] : 'Just a few more data!',
    ];
    $form[$group]['url_second_step'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Url redirect"),
      '#default_value' => isset($config->get($group)['url_second_step']) ? $config->get($group)['url_second_step'] : '/third-step',
      '#maxlength' => 100,
      '#description' => $this->t("Write a URL for redirect"),
    ];
    $group = "third_step";
    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('Third Step'),
      '#open' =>  FALSE,
      '#group' => 'bootstrap',
      '#weight' => 7,
    ];
    $form[$group]['third_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Form Title"),
      '#default_value' => isset($config->get($group)['third_title']) ? $config->get($group)['third_title'] : 'Login App',
      '#maxlength' => 100,
      '#description' => $this->t("Write a title for the form"),
    ];
    $form[$group]['third_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t("Confirmation message"),
      '#format' => 'full_html',
      '#default_value' => isset($config->get($group)['third_message']['value']) ? $config->get($group)['third_message']['value'] : 'User Created, your username and password is:',
    ];
    $form[$group]['url_third_step'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Url redirect"),
      '#default_value' => isset($config->get($group)['url_third_step']) ? $config->get($group)['url_third_step'] : '/first-step',
      '#maxlength' => 100,
      '#description' => $this->t("Write a URL for redirect"),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('multistep.settings')
      ->set('first_step', $form_state->getValue('first_step'))
      ->save();
    $this->config('multistep.settings')
      ->set('second_step', $form_state->getValue('second_step'))
      ->save();
    $this->config('multistep.settings')
      ->set('third_step', $form_state->getValue('third_step'))
      ->save();
    return;
  }

}
