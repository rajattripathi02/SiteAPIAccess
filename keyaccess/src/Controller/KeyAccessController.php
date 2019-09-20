<?php

namespace Drupal\keyaccess\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class KeyAccessController.
 */
class KeyAccessController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function getjsonresponse($api_key, $nid) {
    // Get API KEY if saved.
    $apiKey = \Drupal::state()->get('siteapikey');
    if (!empty($apiKey) && $apiKey == $api_key) {
      // Load node.
      $node = Node::load($nid);
      if ($node && $node->get('type')->target_id == 'page') {
        $json_array = [
          'type' => $node->get('type')->target_id,
          'id' => $node->get('nid')->value,
          'attributes' => [
            'title' => $node->get('title')->value,
            'content' => $node->get('body')->value,
          ],
        ];
        return new JsonResponse($json_array);
      }
    }
    // Return access denied.
    return [
      '#type' => 'markup',
      '#markup' => $this->t('access denied!'),
    ];
  }

}
