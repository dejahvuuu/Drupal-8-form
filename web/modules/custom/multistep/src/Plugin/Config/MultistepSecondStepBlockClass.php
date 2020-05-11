<?php

namespace Drupal\multistep\Plugin\Config;

use Drupal\multistep\Plugin\Block\MultistepSecondStepBlock;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;

/**
 * Manage config of MultistepSecondStepBlock block.
 */
class MultistepSecondStepBlockClass {

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepSecondStepBlock
   */
  protected $instance;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepSecondStepBlock
   */
  protected $configuration;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepSecondStepBlock
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct() {}

  /**
   * {@inheritdoc}
   */
  public function setConfig(MultistepSecondStepBlock &$instance, array &$config) {
    $this->instance = &$instance;
    $this->configuration = &$config;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'others' => [
        'config' => [
          'title' => 'Second Step',
          'description' => '',
          'redirect' => '/third-step',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm(&$form, &$form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build(MultistepSecondStepBlock &$instance, array &$config) {
    $form_builder = \Drupal::formBuilder();
    $this->instance = &$instance;
    $this->configuration = &$config;
    $configFactory = \Drupal::service('config.factory');
    $module_config = $configFactory->get('multistep.settings')->get();
    $secondTitleForm = $module_config['second_step']['second_title'];
    $secondMessageForm = $module_config['second_step']['second_message'];

    $build = [
      '#theme' => 'MultiStepSecondStepBlock',
      '#config' => $this->configuration,
      '#second_title' => $secondTitleForm,
      '#second_description' => $secondMessageForm['value'],
      '#validateForm' => $form_builder->getForm('Drupal\multistep\Form\MultistepSecondStepForm'),
      '#id' => 'second-block-card',
      '#cache' => [
        'max-age' => 0,
      ],
      '#attached' => [
        'library' => [
          'multistep/multistep_secondstep',
        ],
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface &$form_state, &$config) {

  }

}
