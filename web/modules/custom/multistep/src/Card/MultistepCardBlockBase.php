<?php

namespace Drupal\multistep\Card;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\multistep\Card\MultistepCardBlockBaseInterface;

/**
 * Class MultistepCardBlockBase.
 *
 * @package Drupal\multistep
 */
class MultistepCardBlockBase extends BlockBase implements MultistepCardBlockBaseInterface {

  /**
   * Get value.
   *
   * @param string $field
   *   Field name to retrieve.
   *
   * @return mixed
   *   Field value.
   */
  public function getValue($field) {
    return $this->$field;
  }

  /**
   * Set value.
   *
   * @param string $field
   *   Field name to set.
   * @param mixed $value
   *   Value to set the field.
   */
  public function setValue($field, $value) {
    $this->$field = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return parent::build();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
