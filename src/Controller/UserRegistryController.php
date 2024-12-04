<?php
namespace Drupal\user_registry\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserRegistryController extends ControllerBase {

  public function customRegisterForm() {
    return \Drupal::formBuilder()->getForm('Drupal\user_registry\Form\UserRegistryForm');
  }

  public function listUsers() {

    $config = \Drupal::config('user_registry.settings');    
    $header = [
      'ID',
      $this->t($config->get('full_name_label') ?? 'Full Name'),
      $this->t($config->get('email_label') ?? 'Email'),
      $this->t($config->get('dni_label') ?? 'DNI'),
      $this->t($config->get('birth_date_label') ?? 'Birth Date'),
    ];

    $rows = [];

    $query = Database::getConnection()->select('user_registry', 'ur')
      ->fields('ur')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit(10)
      ->orderBy('full_name') 
      ->execute();

    foreach ($query as $record) {
      $rows[] = [
        $record->id,
        $record->full_name,
        $record->email,
        $record->dni,
        $record->birth_date,
      ];
    }

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    $build['pager'] = [
      '#type' => 'pager',
    ];

    return $build;
  }
}
