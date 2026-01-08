<?php

declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Returns responses for Hello World routes.
 */
final class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function hello($name = NULL): array {
    $output = $this->t('Hello World!');

    if ($name) {
      $output = $this->t('Hello @name!', ['@name' => $name]);
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

  /**
   * Builds the response for the hello_name_node route.
   */
  public function helloNameNode($name, $nid): array {
    $node = Node::load($nid);
    // Print object.
    // dpm($node);

    // Get the title field object.
    // dpm($node->get('title'));

    // Print the title of the node.
    // dpm($node->title->value);
    // dpm($node->getTitle());
    // How to print values from complex fields.
    // dpm($node->field_brand->entity->name->value);

    if ($node) {
      $output = $this->t('Hello @name! The title of the node is @title.', [
        '@name' => $name,
        '@title' => $node->getTitle(),
      ]);
    } else {
      $output = $this->t('Hello @name! The node with the ID @id does not exist.', [
        '@name' => $name,
        '@id' => $nid,
      ]);
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

}
