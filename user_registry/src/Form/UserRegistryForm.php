<?php
namespace Drupal\user_registry\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Formulario de registro personalizado.
 */
class UserRegistryForm extends FormBase {

  /**
   * El servicio de configuración.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_registry_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('user_registry.settings');

    $full_name_label = $config->get('full_name_label') ?? $this->t('Full Name');
    $email_label = $config->get('email_label') ?? $this->t('Email Address');
    $dni_label = $config->get('dni_label') ?? $this->t('DNI');
    $birth_date_label = $config->get('birth_date_label') ?? $this->t('Birth Date');

    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $full_name_label,
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $email_label,
      '#required' => TRUE,
    ];

    $form['dni'] = [
      '#type' => 'textfield',
      '#title' => $dni_label,
      '#required' => TRUE,
    ];

    $form['birth_date'] = [
      '#type' => 'date',
      '#title' => $birth_date_label,
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $full_name = $form_state->getValue('full_name');
    $email = $form_state->getValue('email');
    $dni = $form_state->getValue('dni');
    $birth_date = $form_state->getValue('birth_date');

    $connection = Database::getConnection();
    $query = $connection->select('user_registry', 'u')
      ->fields('u', ['dni'])
      ->condition('dni', $dni)
      ->execute();

    if ($query->fetchField()) {
      \Drupal::messenger()->addError($this->t('El DNI ya está registrado.'));
      return;
    }

    $connection->insert('user_registry')
      ->fields([
        'full_name' => $full_name,
        'email' => $email,
        'dni' => $dni,
        'birth_date' => $birth_date,
      ])
      ->execute();

    \Drupal::messenger()->addMessage($this->t('Usuario registrado correctamente.'));
  }
}
