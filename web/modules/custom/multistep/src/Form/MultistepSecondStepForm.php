<?php

namespace Drupal\multistep\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\InvokeCommand;
use CommerceGuys\Addressing\AddressFormat\AddressField;

/**
 * Second step form.
 */
class MultistepSecondStepForm extends FormBase {

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
   * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
   *
   * @var \Drupal\modules\custom\anon_payment_flow\CheckBalanceForm
   */
  protected $error;

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
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('logger.factory'),
      $container->get('tempstore.private')
    );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'multistep_secondstep_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $label
   *   Label for client number input.
   * @param string $url
   *   Url for next step after submmit.
   * @param string $enable_submmit
   *   Value for allow or disallow submmit method.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $configFactory = \Drupal::service('config.factory');
    $module_config = $configFactory->get('multistep.settings')->get();
    $urlRedirectSecondStep = $module_config['second_step']['url_second_step'];
    $multistepServices = \Drupal::service('multistep.services');
    $cityList = $multistepServices->getCityList();

    $form['city'] = [
      '#title' => t('City'),
      '#type' => 'select',
      '#description' => t('Please select your city where do you belong to.'),
      '#required' => 'true',
      '#options' => array('' => t('-Select a City-')) + $cityList,
      '#default_value' => $form_state->getValue('city'),
    ];
    $form['phone'] = [
      '#type' => 'number',
      '#title' => 'Phone',
      '#required' => true,
      '#maxlength' => 10
    ];
    $form['site_location']['address']['site_address'] = [
      '#type' => 'address',
      '#default_value' => \Drupal::config('system.site')->get('address') ?? [
        'country_code' => 'US',
      ],
      '#used_fields' => [
        AddressField::ADDRESS_LINE1,
      ],
      '#available_countries' => ['US', 'CO'],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('SIGUIENTE'),
      '#attributes' => [
        'class' => ['at-button-hight-emphasis'],
      ],
      '#ajax' => [
        'wrapper' => 'my_form_wrapper',
        'callback' => '::ajaxCallback',
      ],
    ];
    $form['u_n_s'] = [
      '#type' => 'hidden',
      '#disabled' => TRUE,
      '#default_value' => $urlRedirectSecondStep,
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Ajax callback.
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    if ($form_state->hasAnyErrors()) {
      $response->addCommand(new InvokeCommand('.at-input-textfield', 'addClass', ['error']));
      return $response;
    }
    else {
      $url_next_step = $form_state->getValue('u_n_s');
      $url = Url::fromUri('internal:' . $url_next_step);
      $command = new RedirectCommand($url->toString());
      $response->addCommand($command);
      unset($_SESSION['messages']);
    }

    return $response;
  }
}
