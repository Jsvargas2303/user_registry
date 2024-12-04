<?php

namespace Drupal\user_registry\Plugin\rest;

use Drupal\rest\ResourceBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides a REST Resource for fetching user by DNI.
 *
 * @RestResource(
 *   id = "user_registry_resource",
 *   label = @Translation("User Registry Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/user/{dni}"
 *   }
 * )
 */
class UserRegistryResource extends ResourceBase {
  public function get($dni) {
    $query = \Drupal::database()->select('user_registry', 'ur')
      ->fields('ur', ['full_name', 'email', 'dni', 'birth_date'])
      ->condition('dni', $dni)
      ->execute()
      ->fetch();

    if ($query) {
      return new JsonResponse($query);
    }

    return new JsonResponse(['error' => 'User not found'], 404);
  }
}
