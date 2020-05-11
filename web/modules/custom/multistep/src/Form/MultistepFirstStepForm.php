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

/**
 * First step form.
 */
class MultistepFirstStepForm extends FormBase {

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
   * Construct
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
    return 'multistep_firststep_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @param string $url
   *   Url for next step after submmit.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state ) {

    $configFactory = \Drupal::service('config.factory');
    $module_config = $configFactory->get('multistep.settings')->get();
    $urlRedirectFirstStep = isset($module_config['first_step']['url_first_step']) ? $module_config['first_step']['url_first_step'] : '';
    $genderOptions = [
      'female' => $this->t('Female'),
      'male' => $this->t('Male'),
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => '',
        'class' => ['at-input-textfield'],
        'autofocus' => '',
      ],
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => '',
        'class' => ['at-input-textfield'],
        'autofocus' => '',
      ],
    ];
    $form['gender'] = [
      '#name' => 'avaliable_cards',
      '#title' => $this->t('Gender'),
      '#options' => $genderOptions,
      '#type' => 'radios',
      '#default_value' => $genderOptions,
      '#required' => TRUE,
    ];
    $form['birthdate'] = [
      '#title' => t('Birthdate'),
      '#type' => 'date',
      '#description' => t('Select your birthdate'),
      '#default_value' => [
        'month' => \Drupal::service('date.formatter')->format(
          time(), 'custom', 'n'
        ),
        'day' => \Drupal::service('date.formatter')->format(
          time(), 'custom', 'j'
        ),
        'year' => \Drupal::service('date.formatter')->format(
          time(), 'custom', 'Y'
        ),
      ],
      '#required' => TRUE,
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
      '#default_value' => $urlRedirectFirstStep,
    ];

    return $form;
  }

  /**
   * Validate Form.
   *
   * @form array $form
   *   Form.
   *
   * @form_state \Drupal\Core\Form\FormStateInterface $form_state
   *   Form State.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

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
      $response->addCommand(new InvokeCommand('.form-radio', 'addClass', ['error']));
      return $response;
    }
    else {
      $params['first_name'] = $form_state->getValue('first_name');
      $params['last_name'] = $form_state->getValue('last_name');
      $params['gender'] = $form_state->getValue('gender');
      $params['birthdate'] = $form_state->getValue('birthdate');
      $tempstore = $this->tempStoreFactory->get('multistep_values');
      $tempstore->set('params', $params);
      $url_next_step = $form_state->getValue('u_n_s');
      $url = Url::fromUri('internal:' . $url_next_step);
      $command = new RedirectCommand($url->toString());
      $response->addCommand($command);
      unset($_SESSION['messages']);
    }

    return $response;
  }
}
