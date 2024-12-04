<?php
namespace Drupal\user_registry\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;

class UserRegistryApiController extends ControllerBase {

  public function getUserByDni($dni) {

    $query = Database::getConnection()->select('user_registry', 'ur')
      ->fields('ur')
      ->condition('dni', $dni)
      ->execute()
      ->fetchObject();

    if ($query) {
      return new JsonResponse([
        'id' => $query->id,
        'full_name' => $query->full_name,
        'email' => $query->email,
        'dni' => $query->dni,
        'birth_date' => $query->birth_date,
      ]);
    } else {
      return new JsonResponse(['error' => 'User not found.'], 404);
    }
  }
}
