<?php

namespace Drupal\multistep\Plugin\Config;

use Drupal\multistep\Plugin\Block\MultistepFirstStepBlock;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;

/**
 * Manage config of MultistepFirstStepBlock block.
 */
class MultistepFirstStepBlockClass {

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepFirstStepBlock
   */
  protected $instance;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepFirstStepBlock
   */
  protected $configuration;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepFirstStepBlock
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct() {}

  /**
   * {@inheritdoc}
   */
  public function setConfig(MultistepFirstStepBlock &$instance, array &$config) {
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
          'title' => 'Login App',
          'description' => '',
          'redirect' => '/second-step',
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
  public function build(MultistepFirstStepBlock &$instance, array &$config) {

    $form_builder = \Drupal::formBuilder();
    $this->instance = &$instance;
    $this->configuration = &$config;
    $configFactory = \Drupal::service('config.factory');
    $module_config = $configFactory->get('multistep.settings')->get();
    $firstTitleForm = $module_config['first_step']['first_title'];
    $firstMessageForm = $module_config['first_step']['first_message'];

    $build = [
      '#theme' => 'MultiStepFirstStepBlock',
      '#config' => $this->configuration,
      '#first_title' => $firstTitleForm,
      '#first_description' => $firstMessageForm['value'],
      '#validateForm' => $form_builder->getForm('Drupal\multistep\Form\MultistepFirstStepForm'),
      '#id' => 'block-card',
      '#cache' => [
        'max-age' => 0,
      ],
      '#attached' => [
        'library' => [
          'multistep/multistep_firststep',
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
