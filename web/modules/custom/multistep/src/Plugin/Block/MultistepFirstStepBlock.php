<?php

namespace Drupal\multistep\Plugin\Block;

use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\multistep\Card\MultistepCardBlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\multistep\Plugin\Config\MultistepFirstStepBlockClass;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'MultistepFirstStepBlock' block.
 *
 * @Block(
 *  id = "multistep_firststep_block",
 *  admin_label = @Translation("Multistep Firststep Block"),
 * )
 */
class MultistepFirstStepBlock extends MultistepCardBlockBase implements ContainerFactoryPluginInterface {

/**
   * @var $configurationInstance \Drupal\multistep\Plugin\Config\MultistepFirstStepBlockClass
   */
  protected $configurationInstance;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\multistep\Plugin\Config\MultistepFirstStepBlockClass  $configurationInstance
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MultistepFirstStepBlockClass $configurationInstance) {
    // Store our dependency.
    $this->configurationInstance = $configurationInstance;

    // Call parent construct method.
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    //Set init config
    $this->configurationInstance->setConfig($this, $this->configuration);
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('multistep.first_step_block')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    if (method_exists($this->configurationInstance,'defaultConfiguration')) {
      return $this->configurationInstance->defaultConfiguration();
    }
    return parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    if (method_exists($this->configurationInstance,'blockAccess')) {
      return $this->configurationInstance->blockAccess($account);
    }
    return parent::blockAccess($account);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'blockForm')) {
      return $this->configurationInstance->blockForm($form, $form_state);
    }
    return parent::blockForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'blockSubmit')) {
      $this->configurationInstance->blockSubmit($form, $form_state, $this->configuration);
    }
    parent::blockSubmit($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'blockValidate')) {
      return $this->configurationInstance->blockValidate($form, $form_state);
    }
    parent::blockValidate($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (method_exists($this->configurationInstance,'build')) {
      return $this->configurationInstance->build($this, $this->configuration);
    }
    return parent::build();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'buildConfigurationForm')) {
      return $this->configurationInstance->buildConfigurationForm($form, $form_state);
    }
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getMachineNameSuggestion() {
    if (method_exists($this->configurationInstance,'getMachineNameSuggestion')) {
      return $this->configurationInstance->getMachineNameSuggestion();
    }
    return parent::getMachineNameSuggestion();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    if (method_exists($this->configurationInstance,'getCacheMaxAge')) {
      return $this->configurationInstance->getCacheMaxAge();
    }
    return parent::getCacheMaxAge();
  }

  /**
   * {@inheritdoc}
   */
  public function setTransliteration(TransliterationInterface $transliteration) {
    if (method_exists($this->configurationInstance,'setTransliteration')) {
      return $this->configurationInstance->setTransliteration($transliteration);
    }
    parent::setTransliteration($transliteration);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'submitConfigurationForm')) {
      return $this->configurationInstance->submitConfigurationForm($form, $form_state);
    }
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    if (method_exists($this->configurationInstance,'validateConfigurationForm')) {
      return $this->configurationInstance->validateConfigurationForm($form, $form_state);
    }
    parent::validateConfigurationForm($form, $form_state);
  }
}
