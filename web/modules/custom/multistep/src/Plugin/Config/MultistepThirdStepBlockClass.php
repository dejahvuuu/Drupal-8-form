<?php

namespace Drupal\multistep\Plugin\Config;

use Drupal\multistep\Plugin\Block\MultistepThirdStepBlock;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;


/**
 * Manage config of MultistepThirdStepBlock block.
 */
class MultistepThirdStepBlockClass {

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepThirdStepBlock
   */
  protected $instance;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepThirdStepBlock
   */
  protected $configuration;

  /**
   * The database connection object for this statement's DatabaseConnection.
   *
   * @var \Drupal\modules\multistep\MultistepThirdStepBlock
   */
  protected $formBuilder;

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Drupal\Core\TempStore\PrivateTempStoreFactory definition.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  private $tempStoreFactory;

  /**
   * Construct.
   */
  public function __construct(
    MessengerInterface $messenger,
    LoggerChannelFactoryInterface $logger_factory,
    PrivateTempStoreFactory $tempStoreFactory
  ) {
    $this->messenger = $messenger;
    $this->loggerFactory = $logger_factory;
    $this->tempStoreFactory = $tempStoreFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfig(MultistepThirdStepBlock &$instance, array &$config) {
    $this->instance = &$instance;
    $this->configuration = &$config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('logger.factory'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'others' => [
        'config' => [
          'title' => 'Third Step',
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
  public function build(MultistepThirdStepBlock &$instance, array &$config) {

    $this->instance = &$instance;
    $this->configuration = &$config;
    $configFactory = \Drupal::service('config.factory');
    $module_config = $configFactory->get('multistep.settings')->get();
    $thirdTitleForm = $module_config['third_step']['third_title'];
    $thirdMessageForm = $module_config['third_step']['third_message'];
    $urlRedirect = $module_config['third_step']['url_third_step'];

    $tempstore = $this->tempStoreFactory->get('multistep_values');
    $params = $tempstore->get('params');

    $multistepServices = \Drupal::service('multistep.services');
    $userPassword = $multistepServices->generatePassword();
    $userMail = strtolower($params['first_name'] . $params['last_name'] . '@multistep.com');
    $userName = $params['first_name'] . $params['last_name'];
    $userBirthday = $params['birthdate'];

    $saveUser = $multistepServices->saveUser($userName, $userPassword, $userMail, $userBirthday);

    $build = [
      '#theme' => 'MultiStepThirdStepBlock',
      '#config' => $this->configuration,
      '#third_title' => $thirdTitleForm,
      '#third_description' => $thirdMessageForm['value'],
      '#password' => $userPassword,
      '#username' => $saveUser,
      '#id' => 'third-block-card',
      '#cache' => [
        'max-age' => 0,
      ],
      '#attached' => [
        'library' => [
          'multistep/multistep_thirdstep',
        ],
        'drupalSettings' => [
          'url_redirect' => $urlRedirect,
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
