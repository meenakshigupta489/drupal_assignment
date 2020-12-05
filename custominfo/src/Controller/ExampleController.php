<?php

namespace Drupal\custominfo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessResult;


/**
 * Class ExampleController.
 */
class ExampleController extends ControllerBase {

  /**
   * Creates json for basic page node.
   *
   *
   * @param $sitekey
   *   The sitekey for site information.
   *
   * @param $node
   *   The node for basic page.
   *
   * @return
   *   Json for node.
   */
  public function content($sitekey,NodeInterface $node) {
    $serializer = \Drupal::service('serializer');
    $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
    return new JsonResponse([
      'data' =>  Json::decode($data),
      'method' => 'GET',
    ]);
  }

  /**
   * Access for controller.
   */
  public function access() {
    $config = [];
    $site_key = \Drupal::routeMatch()->getParameter('sitekey');
    $node = \Drupal::routeMatch()->getParameter('node');
    $config = \Drupal::config('system.site');
    $system_site_key = $config->get('siteapikey');
    if($node->bundle() == "page" && $site_key === $system_site_key){
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    } 
  }

}
